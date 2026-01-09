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
        // Load database if not loaded
        if (!isset($this->CI->db)) {
            $this->CI->load->database();
        }

        // Gather Data
        $data = [
            'user_id' => $this->CI->session->userdata('id'), // Assuming 'id' is session key
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            'url' => current_url(),
            'method' => $this->CI->input->method(TRUE),
            'user_agent' => $this->CI->input->user_agent(),
            // created_at defaults to CURRENT_TIMESTAMP
        ];

        // Insert into traffic_log
        // Using simple query to avoid potential model issues if not loaded
        $this->CI->db->insert('traffic_log', $data);
    }
}
