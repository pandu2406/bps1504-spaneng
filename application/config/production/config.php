<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Inherit the main config
require_once(APPPATH . 'config/config.php');

// Overrides for PRODUCTION
$config['base_url'] = 'https://bps-batanghari.com/spaneng/';
$config['index_page'] = '';
$config['encryption_key'] = 'e9f4a1c6a0f0b9b7d7d8c3b6c9c8f44a6d9f9e8d7c6b5a4f3e2d1c0b9a8';
$config['log_threshold'] = 1;

// Session Overrides (Use Database for reliability on Shared Hosting)
$config['sess_driver'] = 'database';
$config['sess_save_path'] = 'ci_sessions'; // Table name
$config['sess_cookie_name'] = 'ci_session_prod'; // Unique cookie name
$config['cookie_domain'] = '.bps-batanghari.com'; // Allow subdomain access if needed
