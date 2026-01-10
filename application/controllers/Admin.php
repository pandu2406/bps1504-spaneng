<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Role_model');
        $this->load->model('Admin_model');
    }

    public function index()
    {
        // Check for completed activities to notify pengawas
        $this->_check_and_notify_completion();

        $data['title'] = 'Dashboard';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['pegawai'] = $this->db->get('pegawai')->num_rows();

        // Stats Mitra per Tahun
        $data['mitra_2024'] = $this->db->get_where('mitra_tahun', ['tahun' => 2024])->num_rows();
        $data['mitra_2025'] = $this->db->get_where('mitra_tahun', ['tahun' => 2025])->num_rows();
        $data['mitra_2026'] = $this->db->get_where('mitra_tahun', ['tahun' => 2026])->num_rows();

        $now = time();

        $sql_k_berjalan = "SELECT * FROM kegiatan WHERE start <= $now AND finish >= $now";
        $data['k_berjalan'] = $this->db->query($sql_k_berjalan)->num_rows();

        $sql_k_akan_datang = "SELECT * FROM kegiatan WHERE start > $now";
        $data['k_akan_datang'] = $this->db->query($sql_k_akan_datang)->num_rows();

        $sql_k_selesai = "SELECT * FROM kegiatan WHERE finish < $now";
        $data['k_selesai'] = $this->db->query($sql_k_selesai)->num_rows();

        $sql = "SELECT * FROM kegiatan ORDER BY start";
        $data['details'] = $this->db->query($sql)->result_array();

        // Hitung jumlah_kriteria
        $jumlah_kriteria = $this->db->get('kriteria')->num_rows();
        $data['jumlah_kriteria'] = $jumlah_kriteria;

        // Hitung jumlah kegiatan yang belum selesai penilaiannya
        $query_belum_selesai = "
        SELECT 
            k.id,
            k.nama,
            COUNT(DISTINCT akp.id) AS jumlah_mitra,
            COUNT(ap.id) AS jumlah_penilaian
        FROM kegiatan k
        LEFT JOIN all_kegiatan_pencacah akp ON akp.kegiatan_id = k.id
        LEFT JOIN all_penilaian ap ON ap.all_kegiatan_pencacah_id = akp.id
        WHERE k.finish < $now
        GROUP BY k.id
        HAVING jumlah_penilaian < (COUNT(DISTINCT akp.id) * $jumlah_kriteria)
    ";

        $data['jumlah_kegiatan_belum_dinilai'] = $this->db->query($query_belum_selesai)->num_rows();
        $data['belum_dinilai'] = $this->db->query($query_belum_selesai)->result_array();

        // ================================
        // Tambahan: Jumlah Mitra Rekap Belum Lengkap
        // ================================
        $query_rekap_tidak_lengkap = "
            SELECT DISTINCT m.id_mitra, m.nama
            FROM rinciankegiatan r
            JOIN mitra m ON m.id_mitra = r.id_mitra
            WHERE r.beban = '-' OR r.honor = '-' OR r.total_honor = '-' OR r.satuan = '-'
               OR r.beban IS NULL OR r.honor IS NULL OR r.total_honor IS NULL OR r.satuan IS NULL
        ";

        $data['rekap_tidak_lengkap'] = $this->db->query($query_rekap_tidak_lengkap)->result_array();
        $data['jumlah_rekap_tidak_lengkap'] = count($data['rekap_tidak_lengkap']);

        // ================================
        // Leaderboard Mitra Terbaik (Top 3) with Filter
        // ================================
        $filter_bulan = $this->input->get('bulan');
        $filter_tahun = $this->input->get('tahun');

        if ($filter_bulan && $filter_tahun) {
            $where_sql = "WHERE FROM_UNIXTIME(k.finish, '%m') = ? AND FROM_UNIXTIME(k.finish, '%Y') = ?";
            $params = [$filter_bulan, $filter_tahun];
        } elseif ($filter_tahun) {
            $where_sql = "WHERE FROM_UNIXTIME(k.finish, '%Y') = ?";
            $params = [$filter_tahun];
        } else {
            $where_sql = "";
            $params = [];
        }

        $query_top_mitra = "
        SELECT 
            m.id_mitra, m.nama, m.nik, m.jk,
            AVG(p.nilai) as rata_rata,
            COUNT(DISTINCT akp.kegiatan_id) as total_kegiatan
        FROM all_penilaian p
        JOIN all_kegiatan_pencacah akp ON p.all_kegiatan_pencacah_id = akp.id
        JOIN kegiatan k ON akp.kegiatan_id = k.id
        JOIN mitra m ON akp.id_mitra = m.id_mitra
        $where_sql
        GROUP BY m.id_mitra
        HAVING total_kegiatan >= 1
        ORDER BY (AVG(p.nilai) * (1 + (COUNT(DISTINCT akp.kegiatan_id) * 0.05))) DESC 
        LIMIT 3
    ";
        $data['top_mitra'] = $this->db->query($query_top_mitra, $params)->result_array();
        $data['selected_bulan'] = $filter_bulan;
        $data['selected_tahun'] = $filter_tahun;

        // === Load ke view ===
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('template/footer');
    }

    public function get_kegiatan_selesai_count()
    {
        // Prevent any previous output
        if (ob_get_level())
            ob_end_clean();

        if ($this->input->is_ajax_request()) {
            $tahun = $this->input->post('tahun');
            $bulan = $this->input->post('bulan');
            $now = time();

            // Build Query
            $this->db->where('finish <', $now);

            if ($tahun && $tahun != 'all') {
                // Assuming finish is UNIX timestamp
                $this->db->where("FROM_UNIXTIME(finish, '%Y') =", $tahun);
            }
            if ($bulan && $bulan != 'all') {
                $this->db->where("FROM_UNIXTIME(finish, '%m') =", sprintf("%02d", $bulan));
            }

            $count = $this->db->count_all_results('kegiatan');

            header('Content-Type: application/json');
            echo json_encode(['count' => $count]);
            exit;
        } else {
            show_404();
        }
    }



    public function role()
    {
        $data['title'] = 'Role';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['role'] = $this->db->get('user_role')->result_array();

        $this->form_validation->set_rules('role', 'Role', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('admin/role', $data);
            $this->load->view('template/footer');
        } else {
            $this->db->insert('user_role', ['role' => $this->input->post('role')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New role added!</div>');
            redirect('admin/role');
        }
    }

    public function editrole($id)
    {
        $data['title'] = 'Edit Role';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['role'] = $this->db->get_where('user_role', ['id' => $id])->row_array();

        $this->form_validation->set_rules('role', 'Role', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('admin/edit-role', $data);
            $this->load->view('template/footer');
        } else {

            $role = $this->input->post('role');


            $this->db->set('role', $role);
            $this->db->where('id', $id);
            $this->db->update('user_role');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Role has been updated!</div>');
            redirect('admin/role');
        }
    }

    function deleterole($id)
    {
        $this->Role_model->delete($id);
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Role has been deleted!</div>');
        redirect('admin/role');
    }

    public function roleaccess($role_id)
    {
        $data['title'] = 'Role Access';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

        $this->db->where('id !=', 6);
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('admin/role-access', $data);
        $this->load->view('template/footer');
    }

    public function changeaccess()
    {
        $menu_id = $this->input->post('menuId');
        $role_id = $this->input->post('roleId');

        $data = [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ];

        $result = $this->db->get_where('user_access_menu', $data);

        if ($result->num_rows() < 1) {
            $this->db->insert('user_access_menu', $data);
        } else {
            $this->db->delete('user_access_menu', $data);
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Access changed!</div>');
    }

    public function alluser()
    {
        $data['title'] = 'All User';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $query = "SELECT user.*, user_role.role FROM user LEFT JOIN user_role ON user.role_id = user_role.id";
        $data['alluser'] = $this->db->query($query)->result_array();
        // $data['role'] = $this->db->get('user_role')->result_array();
        // $data['seksi'] = $this->db->get('seksi')->result_array();
        // $data['pegawai'] = $this->db->get('pegawai')->result_array();


        // $this->form_validation->set_rules('email', 'Email', 'required|trim');
        // $this->form_validation->set_rules('role_id', 'Role_id', 'required|trim');

        // if ($this->form_validation->run() == false) {
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('admin/all_user', $data);
        $this->load->view('template/footer');
        // } else {
        //     $data = [

        //         'email' => $this->input->post('email'),
        //         'role_id' => $this->input->post('role_id'),
        //         'seksi_id' => $this->input->post('seksi_id'),
        //         'date_created' => time()

        //     ];

        //     $email = $this->input->post('email');
        //     $role_id = $this->input->post('role_id');

        //     $sqlcek = "SELECT * FROM user WHERE email = '$email' AND role_id = $role_id";
        //     $cek = $this->db->query($sqlcek)->num_rows();

        //     if ($cek < 1) {
        //         $this->db->insert('user', $data);
        //         $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New user added!</div>');
        //     } else {
        //         $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">User sudah ada!</div>');
        //     }

        //     redirect('admin/alluser');
        // }
    }

    public function deactivated($id)
    {
        $this->Admin_model->deactivated($id);
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">User has been deactivated!</div>');
        redirect('admin/alluser');
    }

    public function activated($id)
    {
        $this->Admin_model->activated($id);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">User has been activated!</div>');
        $this->Admin_model->delete($id);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">User has been deleted!</div>');
        redirect('admin/alluser');
    }

    public function deleteuser($id)
    {
        $sql = "DELETE FROM user WHERE id = $id";
        $this->db->query($sql);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">User deleted!</div>');
        redirect('admin/alluser');
    }

    private function _check_and_notify_completion()
    {
        // Find activities that finished in the past but haven't sent notifications yet
        // Using UNIX timestamp similar to Kegiatan controller usage
        $now = time();
        $this->db->where('finish <', $now);
        $this->db->where('is_notification_sent', 0);
        $completed_activities = $this->db->get('kegiatan')->result_array();

        foreach ($completed_activities as $activity) {
            $kegiatan_id = $activity['id'];

            // Find all unique Pengawas (Supervisors) for this activity
            // Looking at all_kegiatan_pencacah where id_pengawas is set
            $this->db->distinct();
            $this->db->select('id_pengawas');
            $this->db->where('kegiatan_id', $kegiatan_id);
            $this->db->where('id_pengawas !=', 0);
            $assignments = $this->db->get('all_kegiatan_pencacah')->result_array();

            foreach ($assignments as $assign) {
                $id_pengawas = $assign['id_pengawas'];

                // Get Pengawas Info (Pegawai or Mitra)
                $pengawas = $this->db->get_where('pegawai', ['id_peg' => $id_pengawas])->row_array();
                if (!$pengawas) {
                    $pengawas = $this->db->get_where('mitra', ['id_mitra' => $id_pengawas])->row_array();
                }

                if ($pengawas && !empty($pengawas['email'])) {
                    $subject = "Pemberitahuan Kegiatan Selesai: " . $activity['nama'];
                    $message = "
                        <h3>Halo {$pengawas['nama']},</h3>
                        <p>Kegiatan <b>{$activity['nama']}</b> telah berakhir.</p>
                        <p>Mohon segera lakukan penilaian kinerja terhadap Pencacah yang Anda awasi melalui aplikasi SPANENG.</p>
                        <br>
                        <p>Terima kasih,<br>Admin SPANENG</p>
                    ";

                    $this->_send_notification($pengawas['email'], $subject, $message);
                }
            }

            // Mark activity as notified
            $this->db->where('id', $kegiatan_id);
            $this->db->update('kegiatan', ['is_notification_sent' => 1]);
        }
    }

    private function _send_notification($to, $subject, $message)
    {
        // Load email library if not loaded
        if (!isset($this->email)) {
            $this->load->library('email');
        }

        // Config from TestEmail.php
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.hostinger.com',
            'smtp_user' => 'admin@bps-batanghari.com',
            'smtp_pass' => 'Zxcxz12321.',
            'smtp_port' => 587,
            'smtp_crypto' => 'tls',
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n",
            'crlf' => "\r\n",
            'validation' => TRUE,
            'wordwrap' => TRUE
        );

        $this->email->initialize($config);
        $this->email->set_newline("\r\n");

        $this->email->from('admin@bps-batanghari.com', 'Admin SPANENG');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        if ($this->email->send()) {
            return true;
        } else {
            log_message('error', 'Email error: ' . $this->email->print_debugger());
            return false;
        }
    }
    public function logging()
    {
        $data['title'] = 'Security Command Center';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // 1. Total Requests
        $data['total_requests'] = $this->db->count_all('traffic_log');

        // 2. High Severity Alerts
        $this->db->where('severity', 'critical');
        $this->db->or_where('severity', 'high');
        $data['critical_alerts'] = $this->db->count_all_results('security_alerts');

        // 3. Unique IPs
        $this->db->distinct();
        $this->db->select('ip_address');
        $data['unique_ips'] = $this->db->get('traffic_log')->num_rows();

        // 4. Banned IPs Count
        $data['banned_count'] = $this->db->count_all('banned_ips');

        // 5. Top Active Users
        $data['top_users'] = []; // Initialize to prevent undefined error

        $this->db->select('user_id, COUNT(*) as hits');
        $this->db->where('user_id IS NOT NULL', null, false);
        $this->db->group_by('user_id');
        $this->db->order_by('hits', 'DESC');
        $this->db->limit(5);
        $query_top = $this->db->get('traffic_log');

        if ($query_top) {
            $data['top_users'] = $query_top->result_array();
        }

        // 6. Traffic Trend (24h)
        $sql_trend = "
            SELECT DATE_FORMAT(created_at, '%H:00') as hour, COUNT(*) as count 
            FROM traffic_log 
            WHERE created_at >= NOW() - INTERVAL 24 HOUR 
            GROUP BY hour 
            ORDER BY created_at ASC
        ";
        $data['traffic_trend'] = $this->db->query($sql_trend)->result_array();

        // 7. Alert Feed
        $this->db->order_by('id', 'DESC');
        $this->db->limit(10);
        $data['alerts'] = $this->db->get('security_alerts')->result_array();

        // 7. Banned IPs List
        $data['banned_ips_list'] = $this->db->get('banned_ips')->result_array();

        // 8. Recent Logs
        $this->db->order_by('id', 'DESC');
        $this->db->limit(100);
        $data['logs'] = $this->db->get('traffic_log')->result_array();

        // 9. Interpretation / Security Posture
        $data['posture'] = 'safe';
        $data['posture_text'] = 'System Stable. No critical threats detected.';
        $data['posture_color'] = 'success';

        if ($data['critical_alerts'] > 0) {
            $data['posture'] = 'danger';
            $data['posture_text'] = 'CRITICAL: High severity threats detected! Immediate investigation required.';
            $data['posture_color'] = 'danger';
        } elseif ($data['critical_alerts'] == 0 && count($data['alerts']) > 5) {
            $data['posture'] = 'warning';
            $data['posture_text'] = 'Elevated Risk. Multiple suspicious activities detected.';
            $data['posture_color'] = 'warning';
        }

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('admin/logging', $data);
        $this->load->view('template/footer');
    }

    public function ban_ip()
    {
        $ip = $this->input->post('ip');
        $reason = $this->input->post('reason');

        if ($ip) {
            $this->db->insert('banned_ips', [
                'ip_address' => $ip,
                'reason' => $reason
            ]);
            $this->session->set_flashdata('message', '<div class="alert alert-danger">IP Address Banned!</div>');
        }
        redirect('admin/logging');
    }

    public function unban_ip($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('banned_ips');
        $this->session->set_flashdata('message', '<div class="alert alert-success">IP Address Unbanned!</div>');
        redirect('admin/logging');
    }
}
