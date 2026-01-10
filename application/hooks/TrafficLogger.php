<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TrafficLogger
{

    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function log_request()
    {
        // Check Banned IPs first
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $this->_check_ban($ip_address);

        // Load database if not loaded
        if (!isset($this->CI->db)) {
            $this->CI->load->database();
        }

        // Gather Headers & Body
        $headers = [];
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
        } else {
            // Polyfill or ignore
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
        }
        $headers = json_encode($headers);
        $body = file_get_contents('php://input');

        // Sanitize Body (Mask Passwords)
        $clean_body = $this->_mask_sensitive_data($body);

        // Risk Analysis
        $risk_analysis = $this->_analyze_threat($ip_address, current_url(), $clean_body, $headers);
        $risk_score = $risk_analysis['score'];

        // Gather Data
        $data = [
            'user_id' => $this->CI->session->userdata('id'),
            'ip_address' => $ip_address,
            'url' => current_url(),
            'referrer' => $_SERVER['HTTP_REFERER'] ?? null,
            'method' => $this->CI->input->method(TRUE),
            'user_agent' => $this->CI->input->user_agent(),
            'request_headers' => $headers,
            'request_body' => substr($clean_body, 0, 5000), // Limit storage
            'risk_score' => $risk_score
        ];

        // Insert into traffic_log
        $this->CI->db->insert('traffic_log', $data);
        $log_id = $this->CI->db->insert_id();

        // If high risk, create alert
        if ($risk_score >= 50 && !empty($risk_analysis['rule'])) {
            $alert_data = [
                'log_id' => $log_id,
                'rule_name' => $risk_analysis['rule'],
                'severity' => $risk_score >= 80 ? 'critical' : 'high',
                'details' => $risk_analysis['details'],
                'ip_address' => $ip_address
            ];
            $this->CI->db->insert('security_alerts', $alert_data);
        }
    }

    private function _check_ban($ip)
    {
        if (!isset($this->CI->db))
            $this->CI->load->database();

        $banned = $this->CI->db->get_where('banned_ips', ['ip_address' => $ip])->row();
        if ($banned) {
            header('HTTP/1.1 403 Forbidden');
            die("<h1>403 Forbidden</h1><p>Your IP ($ip) has been banned due to suspicious activity.</p>");
        }
    }

    private function _analyze_threat($ip, $url, $body, $headers)
    {
        $score = 0;
        $rule = null;
        $details = null;

        $patterns = [
            'SQL Injection' => '/(\%27)|(\')|(\-\-)|(\%23)|(#)/i',
            'SQL Injection (Union)' => '/(union.*select)/i',
            'XSS Attack' => '/(<script)|(javascript:)|(onerror=)|(onload=)/i',
            'Path Traversal' => '/(\.\.\/)|(\.\.\\\)/',
            'Command Injection' => '/(;.*system)|(;.*exec)|(;.*passthru)/i'
        ];

        // Check URL
        foreach ($patterns as $name => $regex) {
            if (preg_match($regex, $url)) {
                $score += 50;
                $rule = $name;
                $details = "Pattern match in URL: $name";
            }
        }

        // Check Body
        foreach ($patterns as $name => $regex) {
            if (preg_match($regex, $body)) {
                $score += 80; // Higher risk in body
                $rule = $name;
                $details = "Pattern match in Body: $name";
            }
        }

        return ['score' => min($score, 100), 'rule' => $rule, 'details' => $details];
    }

    private function _mask_sensitive_data($input)
    {
        // Simple regex to mask password fields in JSON or Form data
        $patterns = [
            '/("password"\s*:\s*")[^"]+(")/i',
            '/(password=)[^&]+(&?)/i'
        ];
        $replacements = [
            '$1[MASKED]$2',
            '$1[MASKED]$2'
        ];
        return preg_replace($patterns, $replacements, $input);
    }
}
