<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in_user();
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email')
        ])->row_array();

        $email = $this->session->userdata('email');

        // Ambil nama mitra/pegawai (singkat)
        $sqlpegawai = "SELECT pegawai.nama as nama, pegawai.email as email FROM pegawai WHERE pegawai.email = '$email'
                   UNION (SELECT mitra.nama as nama, mitra.email as email FROM mitra WHERE mitra.email = '$email')";
        $data['pegawai'] = $this->db->query($sqlpegawai)->row_array();

        // $data['mitra_count'] = $this->db->get('mitra')->num_rows(); // Old single count
        $data['mitra_2024'] = $this->db->get_where('mitra_tahun', ['tahun' => 2024])->num_rows();
        $data['mitra_2025'] = $this->db->get_where('mitra_tahun', ['tahun' => 2025])->num_rows();
        $data['mitra_2026'] = $this->db->get_where('mitra_tahun', ['tahun' => 2026])->num_rows();
        // Default display (e.g. 2025)
        $data['mitra_count'] = $data['mitra_2025'];
        $data['pegawai_count'] = $this->db->get('pegawai')->num_rows();

        // Ambil mitra dengan nama kecamatan dan nama desa
        $data['mitra'] = $this->db->query("
        SELECT 
            m.*, 
            kc.nama AS nama_kecamatan, 
            kd.nama AS nama_desa 
        FROM mitra m
        LEFT JOIN kode_kecamatan kc ON kc.kode = m.kecamatan
        LEFT JOIN kode_keldes kd ON kd.kode = CONCAT(m.kecamatan, LPAD(m.desa, 3, '0'))
        WHERE m.email = '$email'
    ")->row_array();

        // Fix: Fetch 'posisi' from mitra_tahun if mitra exists
        if ($data['mitra']) {
            $this->db->order_by('tahun', 'DESC');
            $mitra_tahun = $this->db->get_where('mitra_tahun', ['id_mitra' => $data['mitra']['id_mitra']])->row_array();
            // Use position from year table, fallback to main table, then '-'
            $data['mitra']['posisi'] = $mitra_tahun ? $mitra_tahun['posisi'] : ($data['mitra']['posisi'] ?? '-');
            $data['mitra']['tahun_posisi'] = $mitra_tahun ? $mitra_tahun['tahun'] : '-';
        }

        $now = time();

        $data['k_berjalan'] = $this->db->query("SELECT * FROM kegiatan WHERE start <= $now AND finish >= $now")->num_rows();
        $data['k_akan_datang'] = $this->db->query("SELECT * FROM kegiatan WHERE start > $now")->num_rows();
        $data['k_selesai'] = $this->db->query("SELECT * FROM kegiatan WHERE finish < $now")->num_rows();
        $data['details'] = $this->db->query("SELECT * FROM kegiatan ORDER BY start")->result_array();

        $jumlah_kriteria = $this->db->get('kriteria')->num_rows();
        $data['jumlah_kriteria'] = $jumlah_kriteria;

        // Kegiatan belum dinilai
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
        $data['belum_dinilai'] = $this->db->query($query_belum_selesai)->result_array();

        // Rekap mitra belum lengkap
        $query_rekap_tidak_lengkap = "
        SELECT DISTINCT m.id_mitra, m.nama
        FROM rinciankegiatan r
        JOIN mitra m ON m.id_mitra = r.id_mitra
        WHERE 
            r.kegiatan_id IS NOT NULL AND (
                r.beban = '-' OR r.honor = '-' OR r.total_honor = '-' OR r.satuan = '-' 
                OR r.beban IS NULL OR r.honor IS NULL OR r.total_honor IS NULL OR r.satuan IS NULL
            )
    ";
        $data['rekap_tidak_lengkap'] = $this->db->query($query_rekap_tidak_lengkap)->result_array();
        $data['jumlah_rekap_tidak_lengkap'] = count($data['rekap_tidak_lengkap']);

        // Load views
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('user/index', $data);
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

    public function edit()
    {
        $data['title'] = 'Edit Profile';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // CHECK IF USER IS MITRA (By Email in Mitra Table)
        $is_mitra = $this->db->get_where('mitra', ['email' => $this->session->userdata('email')])->num_rows();

        if ($is_mitra > 0) {
            redirect('user/editprofilemitra');
        }

        $this->form_validation->set_rules('email', 'Email', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('user/edit', $data);
            $this->load->view('template/footer');
        } else {
            $email = $this->input->post('email');

            $upload_image = $_FILES['image'];


            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '2048';
                $config['upload_path'] = './assets/img/profile/';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $old_image = $data['user']['image'];
                    if ($old_image != 'default.jpg') {
                        unlink(FCPATH . 'assets/img/profile/' . $old_image);
                    }

                    $new_image = $this->upload->data('file_name');
                    $this->db->set('image', $new_image);
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $this->upload->display_errors() . '</div>');
                    redirect('user');
                }
            }

            $this->db->set('email', $email);
            $this->db->where('email', $email);
            $this->db->update('user');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Your profile has been updated!</div>');
            redirect('user');
        }
    }

    public function get_desa_ajax()
    {
        // Prevent any previous output
        if (ob_get_level())
            ob_end_clean();

        if ($this->input->is_ajax_request() && $this->input->post('kode_kec')) {
            $kode_kec = $this->input->post('kode_kec');
            $this->db->like('kode', $kode_kec, 'after');
            $desa = $this->db->get('kode_keldes')->result_array();

            header('Content-Type: application/json');
            echo json_encode($desa);
            exit;
        } else {
            show_404();
        }
    }

    public function editprofilemitra()
    {
        $data['title'] = 'Edit Profile';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // Fetch User with Position (Join with mitra_tahun for current or latest year)
        // We prioritize current year (2025/2026 etc) or just show from 'mitra' table and maybe fetch posisi separately
        $email = $this->session->userdata('email');

        // 1. Get Base Mitra Data & Kecamatan Name
        $this->db->select('mitra.*, kode_kecamatan.nama as nama_kecamatan');
        $this->db->from('mitra');
        $this->db->join('kode_kecamatan', 'mitra.kecamatan = kode_kecamatan.kode', 'left');
        $this->db->where('mitra.email', $email);
        $mitra = $this->db->get()->row_array();

        if (!$mitra) {
            // Should not happen for role 4
            redirect('user');
        }

        // 2. Get Posisi from mitra_tahun (Latest)
        $this->db->order_by('tahun', 'DESC');
        $mitra_tahun = $this->db->get_where('mitra_tahun', ['id_mitra' => $mitra['id_mitra']])->row_array();
        $mitra['posisi'] = $mitra_tahun ? $mitra_tahun['posisi'] : '-';
        $mitra['tahun_posisi'] = $mitra_tahun ? $mitra_tahun['tahun'] : '-';

        $data['mitra'] = $mitra;

        // Fetch Kecamatan Data
        $data['kode_kecamatan'] = $this->db->get('kode_kecamatan')->result_array();

        // Fetch Desa Data (Filtered by current kecamatan if exists)
        $kode_kec = $data['mitra']['kecamatan'];
        $this->db->like('kode', $kode_kec, 'after');
        $data['kode_keldes'] = $this->db->get('kode_keldes')->result_array();

        // Get Desa Name for initial display
        $kode_desa_lengkap = $data['mitra']['kecamatan'] . $data['mitra']['desa'];
        $desa_row = $this->db->get_where('kode_keldes', ['kode' => $kode_desa_lengkap])->row_array();
        $data['nama_desa'] = $desa_row ? $desa_row['nama'] : '';

        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required|trim');
        $this->form_validation->set_rules('alamat', 'Alamat', 'trim');
        $this->form_validation->set_rules('no_hp', 'No. HP', 'required|trim');
        $this->form_validation->set_rules('sobat_id', 'Sobat ID', 'trim');
        $this->form_validation->set_rules('kecamatan', 'Kecamatan', 'required|trim');
        $this->form_validation->set_rules('desa', 'Desa', 'required|trim');
        $this->form_validation->set_rules('jk', 'Jenis Kelamin', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('user/editprofilemitra', $data);
            $this->load->view('template/footer');
        } else {

            $email_post = $this->input->post('email'); // Should match session

            $update_data = [
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
                'no_hp' => $this->input->post('no_hp'),
                'jk' => $this->input->post('jk'),
                'sobat_id' => $this->input->post('sobat_id'),
                'kecamatan' => $this->input->post('kecamatan'),
                // Desa logic from view usually sends suffix or full code. Controller handles suffix.
                'desa' => (strlen($this->input->post('desa')) > 3) ? substr($this->input->post('desa'), -3) : $this->input->post('desa')
            ];

            $upload_image = $_FILES['image'];

            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '2048';
                $config['upload_path'] = './assets/img/profile/';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $old_image = $data['user']['image'];
                    if ($old_image != 'default.jpg') {
                        unlink(FCPATH . 'assets/img/profile/' . $old_image);
                    }

                    $new_image = $this->upload->data('file_name');
                    $this->db->set('image', $new_image);
                    $this->db->where('email', $email);
                    $this->db->update('user');
                } else {
                    if ($_FILES['image']['error'] != 4) {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $this->upload->display_errors() . '</div>');
                        redirect('user/editprofilemitra');
                    }
                }
            }

            // Update Mitra
            $this->db->where('email', $email);
            $this->db->update('mitra', $update_data); // Using 'mitra' table directly as it's the view source or actual table

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Profil Anda berhasil diperbarui!</div>');
            redirect('user/editprofilemitra');
        }
    }

    public function changePassword()
    {
        $data['title'] = 'Change Password';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('current_password', 'Current Password', 'required|trim');
        $this->form_validation->set_rules('new_password1', 'New Password', 'required|trim|min_length[8]|matches[new_password2]');
        $this->form_validation->set_rules('new_password2', 'Confirm New Password', 'required|trim|min_length[8]|matches[new_password1]');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('user/changepassword', $data);
            $this->load->view('template/footer');
        } else {
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password1');
            if (!password_verify($current_password, $data['user']['password'])) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong current password!</div>');
                redirect('user/changepassword');
            } else {
                if ($current_password == $new_password) {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">New password cannot be the same as current password!</div>');
                    redirect('user/changepassword');
                } else {
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                    $this->db->set('password', $password_hash);
                    $this->db->where('email', $this->session->userdata('email'));
                    $this->db->update('user');

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Password changed!</div>');
                    redirect('user/changepassword');
                }
            }
        }
    }
}
