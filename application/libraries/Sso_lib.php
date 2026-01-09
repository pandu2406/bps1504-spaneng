<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SSO Library
 * 
 * Library untuk handle SSO authentication dengan BPS
 * Mendukung:
 * - OpenID Connect (untuk Mitra)
 * - SAML 2.0 (untuk Pegawai BPS)
 */
class Sso_lib
{
    protected $CI;
    protected $config;

    public function __construct()
    {
        $this->CI =& get_instance();

        // Load config with error handling
        if (!file_exists(APPPATH . 'config/sso.php')) {
            log_message('error', 'SSO config file not found');
            return;
        }

        $this->CI->load->config('sso');
        $this->config = $this->CI->config->item('sso_mitra');

        if (!$this->config) {
            log_message('error', 'SSO config not loaded properly');
        }
    }

    // ========================================
    // OPENID CONNECT (MITRA) METHODS
    // ========================================

    /**
     * Generate PKCE code verifier and challenge
     */
    public function generate_pkce()
    {
        // Generate random code verifier (43-128 characters)
        $code_verifier = $this->base64url_encode(random_bytes(32));

        // Generate code challenge (SHA-256 hash of verifier)
        $code_challenge = $this->base64url_encode(
            hash('sha256', $code_verifier, true)
        );

        return [
            'code_verifier' => $code_verifier,
            'code_challenge' => $code_challenge
        ];
    }

    /**
     * Initiate Mitra SSO Login (OpenID Connect)
     */
    public function initiate_mitra_login()
    {
        $config = $this->CI->config->item('sso_mitra');

        if (!$config['enabled']) {
            return ['error' => 'SSO Mitra is disabled'];
        }

        // Generate state untuk CSRF protection
        $state = bin2hex(random_bytes(16));
        $this->CI->session->set_userdata('sso_state', $state);

        // Generate PKCE
        $pkce = $this->generate_pkce();
        $this->CI->session->set_userdata('sso_code_verifier', $pkce['code_verifier']);

        // Build authorization URL
        $params = [
            'client_id' => $config['client_id'],
            'redirect_uri' => $config['redirect_uri'],
            'response_type' => $config['response_type'],
            'scope' => $config['scope'],
            'state' => $state,
            'code_challenge' => $pkce['code_challenge'],
            'code_challenge_method' => $config['code_challenge_method'],
            'response_mode' => $config['response_mode']
        ];

        $authorize_url = $config['authorize_url'] . '?' . http_build_query($params);

        return ['redirect_url' => $authorize_url];
    }

    /**
     * Handle Mitra SSO Callback
     */
    public function handle_mitra_callback($code, $state)
    {
        $config = $this->CI->config->item('sso_mitra');

        // Verify state (CSRF protection)
        $saved_state = $this->CI->session->userdata('sso_state');
        if ($state !== $saved_state) {
            return ['error' => 'Invalid state parameter'];
        }

        // Get code verifier from session
        $code_verifier = $this->CI->session->userdata('sso_code_verifier');

        // Exchange code for token
        $token_response = $this->exchange_code_for_token($code, $code_verifier, 'mitra');

        if (isset($token_response['error'])) {
            return $token_response;
        }

        // Get user info
        $user_info = $this->get_user_info($token_response['access_token'], 'mitra');

        if (isset($user_info['error'])) {
            return $user_info;
        }

        // Clean up session
        $this->CI->session->unset_userdata(['sso_state', 'sso_code_verifier']);

        return [
            'success' => true,
            'user_info' => $user_info,
            'access_token' => $token_response['access_token']
        ];
    }

    /**
     * Exchange authorization code for access token
     */
    private function exchange_code_for_token($code, $code_verifier, $type = 'mitra')
    {
        $config = $this->CI->config->item('sso_' . $type);

        $post_data = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $config['redirect_uri'],
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'code_verifier' => $code_verifier
        ];

        $ch = curl_init($config['token_url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200) {
            return ['error' => 'Failed to exchange code for token', 'response' => $response];
        }

        return json_decode($response, true);
    }

    /**
     * Get user info from SSO
     */
    private function get_user_info($access_token, $type = 'mitra')
    {
        $config = $this->CI->config->item('sso_' . $type);

        $ch = curl_init($config['userinfo_url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $access_token
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200) {
            return ['error' => 'Failed to get user info', 'response' => $response];
        }

        return json_decode($response, true);
    }

    // ========================================
    // SAML (PEGAWAI BPS) METHODS
    // ========================================

    /**
     * Initiate Pegawai SSO Login (SAML)
     */
    public function initiate_pegawai_login()
    {
        $config = $this->CI->config->item('sso_pegawai');

        if (!$config['enabled']) {
            return ['error' => 'SSO Pegawai is disabled'];
        }

        // Generate SAML Request
        $saml_request = $this->generate_saml_request($config);

        // Generate RelayState
        $relay_state = bin2hex(random_bytes(16));
        $this->CI->session->set_userdata('saml_relay_state', $relay_state);

        // Build SSO URL
        $params = [
            'SAMLRequest' => $saml_request,
            'RelayState' => $relay_state
        ];

        $sso_url = $config['sso_url'] . '?' . http_build_query($params);

        return ['redirect_url' => $sso_url];
    }

    /**
     * Generate SAML Authentication Request
     */
    private function generate_saml_request($config)
    {
        $request_id = '_' . bin2hex(random_bytes(16));
        $issue_instant = gmdate('Y-m-d\TH:i:s\Z');

        $saml_request = <<<XML
<samlp:AuthnRequest 
    xmlns:samlp="urn:oasis:names:tc:SAML:2.0:protocol"
    xmlns:saml="urn:oasis:names:tc:SAML:2.0:assertion"
    ID="{$request_id}"
    Version="2.0"
    IssueInstant="{$issue_instant}"
    Destination="{$config['sso_url']}"
    AssertionConsumerServiceURL="{$config['sp_acs_url']}"
    ProtocolBinding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST">
    <saml:Issuer>{$config['sp_entity_id']}</saml:Issuer>
    <samlp:NameIDPolicy Format="{$config['name_id_format']}" AllowCreate="true"/>
</samlp:AuthnRequest>
XML;

        // Deflate and base64 encode
        $deflated = gzdeflate($saml_request);
        $encoded = base64_encode($deflated);

        return urlencode($encoded);
    }

    /**
     * Handle SAML Response
     */
    public function handle_saml_response($saml_response, $relay_state)
    {
        // Verify RelayState
        $saved_relay_state = $this->CI->session->userdata('saml_relay_state');
        if ($relay_state !== $saved_relay_state) {
            return ['error' => 'Invalid RelayState'];
        }

        // Decode SAML Response
        $decoded = base64_decode($saml_response);

        // Parse SAML Response (simplified - in production use proper SAML library)
        $user_info = $this->parse_saml_response($decoded);

        if (isset($user_info['error'])) {
            return $user_info;
        }

        // Clean up session
        $this->CI->session->unset_userdata('saml_relay_state');

        return [
            'success' => true,
            'user_info' => $user_info
        ];
    }

    /**
     * Parse SAML Response (Simplified)
     */
    private function parse_saml_response($saml_xml)
    {
        // TODO: Implement proper SAML response parsing
        // For production, use library seperti php-saml atau SimpleSAMLphp

        try {
            $xml = new SimpleXMLElement($saml_xml);
            $xml->registerXPathNamespace('saml', 'urn:oasis:names:tc:SAML:2.0:assertion');

            // Extract attributes
            $attributes = [];
            $attribute_nodes = $xml->xpath('//saml:Attribute');

            foreach ($attribute_nodes as $attr) {
                $name = (string) $attr['Name'];
                $value = (string) $attr->AttributeValue;
                $attributes[$name] = $value;
            }

            return $attributes;
        } catch (Exception $e) {
            return ['error' => 'Failed to parse SAML response: ' . $e->getMessage()];
        }
    }

    // ========================================
    // COMMON METHODS
    // ========================================

    /**
     * Match user by email and get role
     */
    public function match_user_by_email($email)
    {
        $this->CI->load->database();

        // Find user by email
        $user = $this->CI->db->get_where('user', ['email' => $email])->row_array();

        if ($user) {
            return [
                'found' => true,
                'user' => $user
            ];
        }

        // User not found - check if auto-create is enabled
        $general_config = $this->CI->config->item('sso_general');

        if ($general_config['auto_create_user']) {
            // Determine role based on email domain
            $role_id = $this->determine_role_by_email($email);

            return [
                'found' => false,
                'auto_create' => true,
                'role_id' => $role_id
            ];
        }

        return [
            'found' => false,
            'auto_create' => false
        ];
    }

    /**
     * Determine role based on email domain
     */
    private function determine_role_by_email($email)
    {
        $email_mapping = $this->CI->config->item('email_role_mapping');
        $domain = substr(strrchr($email, "@"), 1);

        return $email_mapping[$domain] ?? $email_mapping['default'];
    }

    /**
     * Sync user profile from SSO data
     */
    public function sync_user_profile($user_id, $sso_data, $provider)
    {
        $general_config = $this->CI->config->item('sso_general');

        if (!$general_config['auto_update_profile']) {
            return false;
        }

        $this->CI->load->database();

        // Prepare update data
        $update_data = [
            'sso_provider' => $provider,
            'last_sso_sync' => time()
        ];

        // Map SSO fields to database fields
        $field_mapping = [
            'email' => 'email',
            'name' => 'nama',
            'given_name' => 'nama',
            'phone' => 'no_hp',
            'phone_number' => 'no_hp'
        ];

        foreach ($field_mapping as $sso_field => $db_field) {
            if (isset($sso_data[$sso_field]) && in_array($db_field, $general_config['sync_fields'])) {
                $update_data[$db_field] = $sso_data[$sso_field];
            }
        }

        // Update user
        $this->CI->db->where('id', $user_id);
        $this->CI->db->update('user', $update_data);

        return true;
    }

    /**
     * Base64 URL encode
     */
    private function base64url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Base64 URL decode
     */
    private function base64url_decode($data)
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
