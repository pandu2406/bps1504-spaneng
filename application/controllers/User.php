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

    $data['mitra_count'] = $this->db->get('mitra')->num_rows();
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

    public function edit()
    {
        $data['title'] = 'Edit Profile';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

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

    public function editprofilemitra()
    {
        $data['title'] = 'Edit Profile';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['mitra'] = $this->db->get_where('mitra', ['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat');
        $this->form_validation->set_rules('no_hp', 'No. HP', 'required');
        $this->form_validation->set_rules('pendidikan', 'Pendidikan');
        $this->form_validation->set_rules('pekerjaan', 'Pekerjaan Utama');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('user/editprofilemitra', $data);
            $this->load->view('template/footer');
        } else {

            $email = $this->input->post('email');
            $data = [

                'nama' => $this->input->post('nama'),
                'email' => $this->input->post('email'),
                'alamat' => $this->input->post('alamat'),
                'no_hp' => $this->input->post('no_hp'),
                'pendidikan' => $this->input->post('pendidikan'),
                'pekerjaan' => $this->input->post('pekerjaan')
            ];

            $data2 = [
                'email' => $this->input->post('email')
            ];

            // $upload_image = $_FILES['image'];

            // if ($upload_image) {
            //     $config['allowed_types'] = 'gif|jpg|png';
            //     $config['max_size'] = '2048';
            //     $config['upload_path'] = './assets/img/profile/';

            //     $this->load->library('upload', $config);

            //     if ($this->upload->do_upload('image')) {
            //         $old_image = $data['user']['image'];
            //         if ($old_image != 'default.jpg') {
            //             unlink(FCPATH . 'assets/img/profile/' . $old_image);
            //         }

            //         $new_image = $this->upload->data('file_name');
            //         $this->db->set('image', $new_image);
            //         $this->db->where('email', $email);
            //         $this->db->update('user');
            //     } else {
            //         $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $this->upload->display_errors() . '</div>');
            //         redirect('user');
            //     }
            // }

            $this->db->set($data);
            $this->db->where('email', $email);
            $this->db->update('mitra');

            $this->db->set($data2);
            $this->db->where('email', $email);
            $this->db->update('user');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Your profile has been updated!</div>');
            redirect('user');
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
