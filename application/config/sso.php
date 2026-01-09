<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| SSO Configuration
|--------------------------------------------------------------------------
| 
| Konfigurasi untuk Single Sign-On (SSO) BPS
| 
| CARA SETTING CREDENTIAL:
| 1. Untuk SSO Mitra (OpenID Connect):
|    - Dapatkan Client Secret dari admin SSO BPS
|    - Update 'client_secret' di $config['sso_mitra']
|    - Update 'redirect_uri' sesuai domain production Anda
| 
| 2. Untuk SSO Pegawai (SAML):
|    - Dapatkan SAML Metadata XML dari admin SSO BPS
|    - Simpan certificate di folder application/config/saml/
|    - Update path certificate di $config['sso_pegawai']
|
*/

// ========================================
// SSO MITRA (OpenID Connect + PKCE)
// ========================================
$config['sso_mitra'] = [
    'enabled' => true,
    'provider_name' => 'SSO Mitra BPS',

    // OpenID Connect Endpoints
    'base_url' => 'https://sso.bps.go.id/auth/realms/eksternal/protocol/openid-connect',
    'authorize_url' => 'https://sso.bps.go.id/auth/realms/eksternal/protocol/openid-connect/auth',
    'token_url' => 'https://sso.bps.go.id/auth/realms/eksternal/protocol/openid-connect/token',
    'userinfo_url' => 'https://sso.bps.go.id/auth/realms/eksternal/protocol/openid-connect/userinfo',
    'logout_url' => 'https://sso.bps.go.id/auth/realms/eksternal/protocol/openid-connect/logout',

    // Client Configuration
    'client_id' => '03340-mitra-gnm',
    'client_secret' => 'YOUR_CLIENT_SECRET_HERE', // TODO: Ganti dengan client secret yang sebenarnya

    // Redirect URI (Update sesuai domain production)
    'redirect_uri' => base_url('auth/sso_callback/mitra'),

    // Scope
    'scope' => 'openid profile email',

    // PKCE Configuration
    'use_pkce' => true,
    'code_challenge_method' => 'S256', // SHA-256

    // Response Mode
    'response_type' => 'code',
    'response_mode' => 'query',

    // Session
    'session_prefix' => 'sso_mitra_',
];

// ========================================
// SSO PEGAWAI BPS (SAML 2.0)
// ========================================
$config['sso_pegawai'] = [
    'enabled' => true,
    'provider_name' => 'SSO Pegawai BPS',

    // SAML Endpoints
    'idp_entity_id' => 'https://sso.bps.go.id/auth/realms/pegawai-bps',
    'sso_url' => 'https://sso.bps.go.id/auth/realms/pegawai-bps/protocol/saml',
    'slo_url' => 'https://sso.bps.go.id/auth/realms/pegawai-bps/protocol/saml/logout',

    // Service Provider (SP) Configuration
    'sp_entity_id' => base_url(), // Your application URL
    'sp_acs_url' => base_url('auth/saml_callback'), // Assertion Consumer Service URL
    'sp_sls_url' => base_url('auth/saml_logout'), // Single Logout Service URL

    // Certificate & Key (TODO: Update dengan certificate yang sebenarnya)
    'sp_cert_path' => APPPATH . 'config/saml/sp.crt',
    'sp_key_path' => APPPATH . 'config/saml/sp.key',
    'idp_cert_path' => APPPATH . 'config/saml/idp.crt',

    // SAML Settings
    'name_id_format' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
    'requested_authn_context' => false,

    // Session
    'session_prefix' => 'sso_pegawai_',
];

// ========================================
// GENERAL SSO SETTINGS
// ========================================
$config['sso_general'] = [
    // Auto-create user jika email tidak ditemukan di database
    'auto_create_user' => false, // Set true untuk auto-create

    // Default role untuk user baru (jika auto_create_user = true)
    'default_role_id' => 3, // Role ID untuk user baru

    // Auto-update profile dari SSO
    'auto_update_profile' => true,

    // Fields yang akan di-sync dari SSO
    'sync_fields' => ['email', 'nama', 'no_hp'],

    // Session timeout (dalam detik)
    'session_timeout' => 3600, // 1 jam

    // Debug mode (set false di production)
    'debug_mode' => true,
];

// ========================================
// EMAIL TO ROLE MAPPING
// ========================================
// Mapping domain email ke role_id
$config['email_role_mapping'] = [
    // Email dengan domain @bps.go.id = Admin/Pegawai
    'bps.go.id' => 1, // Role ID 1 = Admin

    // Email lainnya = Mitra
    'default' => 3, // Role ID 3 = Mitra/User
];
