<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        if ($this->session->userdata('email')) {
            redirect('user');
        }

        $data['role'] = $this->db->get('user_role')->result_array();

        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Login';
            $this->load->view('template/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('template/auth_footer');
        } else {
            $this->_login();
        }
    }

    private function _login()
    {
        $email = $this->input->post('email', TRUE);
        $password = $this->input->post('password');

        // ===============================
        // 1. RATE LIMIT LOGIN
        // ===============================
        $attempt = (int) $this->session->userdata('login_attempt');
        $lock_until = (int) $this->session->userdata('login_lock_until');

        if ($lock_until && time() < $lock_until) {
            $sisa = ceil(($lock_until - time()) / 60);
            $this->session->set_flashdata(
                'message',
                '<div class="alert alert-danger">Terlalu banyak percobaan login. Coba lagi dalam '
                . $sisa . ' menit.</div>'
            );
            redirect('auth');
            return;
        }

        // ===============================
        // 2. CEK USER (AUTO-DETECT ROLE)
        // ===============================
        // Cari user berdasarkan email saja, tanpa role_id
        $user = $this->db->get_where('user', [
            'email' => $email
        ])->row_array();

        if ($user) {

            if ($user['is_active'] == 1) {

                // ===============================
                // 3. CEK PASSWORD
                // ===============================
                if (password_verify($password, $user['password'])) {

                    // ✅ LOGIN SUKSES → RESET ATTEMPT
                    $this->session->unset_userdata([
                        'login_attempt',
                        'login_lock_until'
                    ]);

                    $data = [
                        'email' => $user['email'],
                        'role_id' => $user['role_id'],
                        'seksi_id' => $user['seksi_id']
                    ];
                    $this->session->set_userdata($data);

                    if ($user['role_id'] == 1) {
                        redirect('admin');
                    } else {
                        redirect('user');
                    }

                } else {

                    // ❌ PASSWORD SALAH → TAMBAH ATTEMPT
                    $attempt++;
                    $this->session->set_userdata('login_attempt', $attempt);

                    if ($attempt >= 5) {
                        $this->session->set_userdata(
                            'login_lock_until',
                            time() + (10 * 60) // 10 menit
                        );
                    }

                    $this->session->set_flashdata(
                        'message',
                        '<div class="alert alert-danger">Email / Password salah.</div>'
                    );
                    redirect('auth');
                }

            } else {
                $this->session->set_flashdata(
                    'message',
                    '<div class="alert alert-danger">Akun belum diaktifkan.</div>'
                );
                redirect('auth');
            }

        } else {

            // ❌ USER TIDAK DITEMUKAN → TETAP HITUNG ATTEMPT
            $attempt++;
            $this->session->set_userdata('login_attempt', $attempt);

            if ($attempt >= 5) {
                $this->session->set_userdata(
                    'login_lock_until',
                    time() + (10 * 60)
                );
            }

            $this->session->set_flashdata(
                'message',
                '<div class="alert alert-danger">Email / Password salah.</div>'
            );
            redirect('auth');
        }
    }


    private function _sendEmail($token, $type)
    {
        // Force load a fresh instance if possible or re-initialize completely
        if (isset($this->email)) {
            $this->email->clear(TRUE);
        } else {
            $this->load->library('email');
        }

        // Config exactly matching Kegiatan.php (known good)
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.hostinger.com',
            'smtp_user' => 'admin@bps-batanghari.com',
            'smtp_pass' => 'Zxcxz12321.',
            'smtp_port' => 587,
            'smtp_crypto' => 'tls',
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'crlf' => "\r\n",
            'validation' => TRUE,
            'wordwrap' => TRUE,
            'smtp_timeout' => 20
        );

        $this->email->initialize($config);
        $this->email->set_newline("\r\n");

        $this->email->from('admin@bps-batanghari.com', 'Admin SPANENG');
        $this->email->to($this->input->post('email'));

        if ($type == 'verify') {
            $this->email->subject('Account Verification - SPANENG');
            $this->email->message('Click this link to verify your account : <a href="' . base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Activate</a>');
        } else if ($type == 'forgot') {
            $this->email->subject('Reset Password - SPANENG');
            $this->email->message('Click this link to reset your password : <a href="' . base_url() . 'auth/resetpassword?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Reset Password</a>');
        }

        if ($this->email->send()) {
            return true;
        } else {
            // Log the specific error for debugging
            log_message('error', 'Auth Email Error: ' . $this->email->print_debugger());
            return false;
        }
    }


    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');
        $this->session->unset_userdata('sso_login');
        $this->session->unset_userdata('sso_provider');

        // Don't set flashdata to avoid ghost messages
        redirect('auth');
    }

    public function blocked()
    {
        $this->load->view('auth/blocked');
    }

    public function forgotPassword()
    {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Forgot Password';
            $this->load->view('template/auth_header', $data);
            $this->load->view('auth/forgot-password');
            $this->load->view('template/auth_footer');
        } else {
            $email = $this->input->post('email');
            $user = $this->db->get_where('user', ['email' => $email, 'is_active' => 1])->row_array();

            if ($user) {
                $token = base64_encode(random_bytes(32));
                $user_token = [
                    'token' => $token,
                    'date_created_token' => time()
                ];

                $this->db->where('email', $email);
                $this->db->update('user', $user_token);

                if ($this->_sendEmail($token, 'forgot')) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Link reset password telah dikirim ke email Anda!</div>');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal mengirim email. Silakan coba lagi nanti.</div>');
                }

                redirect('auth/forgotpassword');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email tidak terdaftar atau belum aktif!</div>');
                redirect('auth/forgotpassword');
            }
        }
    }

    public function resetPassword()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        if ($user) {
            $user_token = $this->db->get_where('user', ['token' => $token])->row_array();

            if ($user_token) {
                $this->session->set_userdata('reset_email', $email);
                $this->changePassword();
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Reset password failed! Wrong token</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Reset password failed! Wrong email</div>');
            redirect('auth');
        }
    }

    public function changePassword()
    {
        if (!$this->session->userdata('reset_email')) {
            redirect('auth');
        }

        $this->form_validation->set_rules('password1', 'Password', 'trim|required|min_length[8]|matches[password2]');
        $this->form_validation->set_rules('password2', 'Password', 'trim|required|min_length[8]|matches[password1]');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Change Password';
            $this->load->view('template/auth_header', $data);
            $this->load->view('auth/change-password');
            $this->load->view('template/auth_footer');
        } else {
            $password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT);
            $email = $this->session->userdata('reset_email');

            $this->db->set('password', $password);
            $this->db->where('email', $email);
            $this->db->update('user');

            $this->session->unset_userdata('reset_email');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Password has been changed! Please login.</div>');
            redirect('auth');
        }
    }

    // ========================================
    // SSO METHODS
    // ========================================

    /**
     * Initiate SSO Mitra Login
     */
    public function sso_mitra()
    {
        $this->load->library('sso_lib');

        $result = $this->sso_lib->initiate_mitra_login();

        if (isset($result['error'])) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">' . $result['error'] . '</div>');
            redirect('auth');
        }

        // Redirect to SSO provider
        redirect($result['redirect_url']);
    }

    /**
     * Initiate SSO Pegawai Login
     */
    public function sso_pegawai()
    {
        $this->load->library('sso_lib');

        $result = $this->sso_lib->initiate_pegawai_login();

        if (isset($result['error'])) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">' . $result['error'] . '</div>');
            redirect('auth');
        }

        // Redirect to SSO provider
        redirect($result['redirect_url']);
    }

    /**
     * SSO Callback Handler (for OpenID Connect)
     */
    public function sso_callback($provider = 'mitra')
    {
        $this->load->library('sso_lib');

        // Get callback parameters
        $code = $this->input->get('code');
        $state = $this->input->get('state');
        $error = $this->input->get('error');

        // Check for errors
        if ($error) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">SSO Error: ' . $error . '</div>');
            redirect('auth');
        }

        if (!$code || !$state) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Invalid SSO callback</div>');
            redirect('auth');
        }

        // Handle callback based on provider
        if ($provider === 'mitra') {
            $result = $this->sso_lib->handle_mitra_callback($code, $state);
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Unknown SSO provider</div>');
            redirect('auth');
        }

        if (isset($result['error'])) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">' . $result['error'] . '</div>');
            redirect('auth');
        }

        // Process SSO login
        $this->_process_sso_login($result['user_info'], $provider);
    }

    /**
     * SAML Callback Handler
     */
    public function saml_callback()
    {
        $this->load->library('sso_lib');

        // Get SAML Response
        $saml_response = $this->input->post('SAMLResponse');
        $relay_state = $this->input->post('RelayState');

        if (!$saml_response) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Invalid SAML response</div>');
            redirect('auth');
        }

        $result = $this->sso_lib->handle_saml_response($saml_response, $relay_state);

        if (isset($result['error'])) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">' . $result['error'] . '</div>');
            redirect('auth');
        }

        // Process SSO login
        $this->_process_sso_login($result['user_info'], 'pegawai');
    }

    /**
     * Process SSO Login
     */
    private function _process_sso_login($user_info, $provider)
    {
        $this->load->library('sso_lib');

        // Extract email from SSO data
        $email = $user_info['email'] ?? $user_info['mail'] ?? null;

        if (!$email) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Email not found in SSO response</div>');
            redirect('auth');
        }

        // Match user by email
        $match_result = $this->sso_lib->match_user_by_email($email);

        if ($match_result['found']) {
            // User exists - login
            $user = $match_result['user'];

            // Check if active
            if ($user['is_active'] != 1) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Akun belum diaktifkan</div>');
                redirect('auth');
            }

            // Sync profile from SSO
            $this->sso_lib->sync_user_profile($user['id'], $user_info, $provider);

            // Set session
            $session_data = [
                'email' => $user['email'],
                'role_id' => $user['role_id'],
                'seksi_id' => $user['seksi_id'],
                'sso_login' => true,
                'sso_provider' => $provider
            ];
            $this->session->set_userdata($session_data);

            // Redirect based on role
            if ($user['role_id'] == 1) {
                redirect('admin');
            } else {
                redirect('user');
            }

        } else {
            // User not found
            if ($match_result['auto_create']) {
                // Auto-create user (if enabled in config)
                $this->_create_user_from_sso($user_info, $provider, $match_result['role_id']);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Email tidak terdaftar. Silakan hubungi administrator.</div>');
                redirect('auth');
            }
        }
    }

    /**
     * Create user from SSO data
     */
    private function _create_user_from_sso($user_info, $provider, $role_id)
    {
        $email = $user_info['email'] ?? $user_info['mail'];
        $name = $user_info['name'] ?? $user_info['given_name'] ?? $email;

        $user_data = [
            'email' => $email,
            'password' => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT), // Random password
            'role_id' => $role_id,
            'is_active' => 1,
            'date_created' => time(),
            'sso_provider' => $provider,
            'last_sso_sync' => time()
        ];

        $this->db->insert('user', $user_data);
        $user_id = $this->db->insert_id();

        // Set session
        $session_data = [
            'email' => $email,
            'role_id' => $role_id,
            'sso_login' => true,
            'sso_provider' => $provider
        ];
        $this->session->set_userdata($session_data);

        $this->session->set_flashdata('message', '<div class="alert alert-success">Akun berhasil dibuat. Selamat datang!</div>');

        // Redirect based on role
        if ($role_id == 1) {
            redirect('admin');
        } else {
            redirect('user');
        }
    }
}
