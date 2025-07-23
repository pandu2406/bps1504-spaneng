<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Master_model');
    }

    public function index()
    {
        $data['title'] = 'Master';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('master/index', $data);
        $this->load->view('template/footer');
    }

    public function mitra()
    {
            // Tangani AJAX request untuk desa berdasarkan kecamatan
        if ($this->input->is_ajax_request() && $this->input->post('kode_kec')) {
            $kode_kec = $this->input->post('kode_kec');

            $this->db->like('kode', $kode_kec, 'after');
            $desa = $this->db->get('kode_keldes')->result_array();

            echo json_encode($desa);
            return; // Hentikan proses controller setelah response AJAX
        }

        $data['title'] = 'Data Mitra';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->db->select('mitra.*, kode_kecamatan.nama as nama_kecamatan');
        $this->db->from('mitra');
        $this->db->join('kode_kecamatan', 'mitra.kecamatan = kode_kecamatan.kode');
        $data['mitra'] = $this->db->get()->result_array();

        $data['kec'] = $this->db->get('kode_kecamatan')->result_array();


        $this->form_validation->set_rules('nik', 'NIK', 'required|trim');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('posisi', 'Posisi', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim');
        $this->form_validation->set_rules('kecamatan', 'Kode Kecamatan', 'required|trim');
        $this->form_validation->set_rules('desa', 'Kode Desa', 'required|trim');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');
        $this->form_validation->set_rules('jk', 'Jenis Kelamin', 'required|trim');
        $this->form_validation->set_rules('no_hp', 'No. HP', 'required|trim');
        $this->form_validation->set_rules('sobat_id', 'Sobat ID', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('master/mitra', $data);
            $this->load->view('template/footer');
        } else {
            $data = [
                'nik' => $this->input->post('nik'),
                'nama' => $this->input->post('nama'),
                'posisi' => $this->input->post('posisi'),
                'email' => $this->input->post('email'),
                'kecamatan' => $this->input->post('kecamatan'),
                'desa' => substr($this->input->post('desa'), 3),
                'alamat' => $this->input->post('alamat'),
                'jk' => $this->input->post('jk'),
                'no_hp' => $this->input->post('no_hp'),
                'sobat_id' => $this->input->post('sobat_id')
            ];

            $check = $this->Master_model->check_email($this->input->post('email'));

            if ($check < 1) {
                $this->db->insert('mitra', $data);
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New mitra added!</div>');
                redirect('master/mitra');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Mitra sudah ada!</div>');
                redirect('master/mitra');
            }
        }
    }

    
    public function import()
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    echo "DEBUG START<br>"; // Debug awal

    $this->form_validation->set_rules('excel', 'File', 'trim|required');

    if ($_FILES['excel']['name'] == '') {
        echo "File tidak ditemukan<br>";
        exit;
    }

    $config['upload_path'] = FCPATH . 'assets/excel/';
    $config['allowed_types'] = 'xls|xlsx';

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('excel')) {
        echo "Gagal upload: " . $this->upload->display_errors();
        exit;
    }

    $data = $this->upload->data();
    echo "Upload berhasil: " . $data['file_name'] . "<br>";

    date_default_timezone_set('Asia/Jakarta');

    include './assets/phpexcel/Classes/PHPExcel/IOFactory.php';

    $inputFileName = './assets/excel/' . $data['file_name'];

    try {
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
    } catch (Exception $e) {
        echo 'Error loading file: ', $e->getMessage();
        exit;
    }

    $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
    echo "Jumlah baris Excel: " . count($sheetData) . "<br>";

    $index = 0;
    $resultData = [];

    foreach ($sheetData as $key => $value) {
        if ($key != 1) {
            if (!isset($value['D'])) continue;

            $check = $this->Master_model->check_email($value['D']);

            if ($check != 1) {
                $resultData[$index]['nik'] = $value['A'] ?? '';
                $resultData[$index]['nama'] = $value['B'] ?? '';
                $resultData[$index]['posisi'] = $value['C'] ?? '';
                $resultData[$index]['email'] = $value['D'] ?? '';
                $resultData[$index]['kecamatan'] = $value['E'] ?? '';
                $resultData[$index]['desa'] = $value['F'] ?? '';
                $resultData[$index]['alamat'] = $value['G'] ?? '';
                $resultData[$index]['jk'] = $value['H'] ?? '';
                $resultData[$index]['no_hp'] = $value['I'] ?? '';
                $resultData[$index]['sobat_id'] = $value['J'] ?? '';
            }
        }
        $index++;
    }

    unlink('./assets/excel/' . $data['file_name']);

    echo "Data berhasil diproses. Total: " . count($resultData) . "<br>";

    if (count($resultData) > 0) {
        $result = $this->Master_model->insert_batch($resultData);

        echo "Insert batch result: $result <br>";

        if ($result > 0) {
            echo "Import berhasil!";
        } else {
            echo "Gagal menyimpan ke database.";
        }
    } else {
        echo "Tidak ada data untuk diimport.";
    }

    exit;
}


    public function import_pegawai()
    {
        ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    echo "DEBUG START<br>"; // Debug awal
        $this->form_validation->set_rules('excel', 'File', 'trim|required');
        if ($_FILES['excel']['name'] == '') {
            $this->session->set_flashdata('msg', 'File harus diisi');
        } else {
            $config['upload_path'] = FCPATH . 'assets/excel/';
            $config['allowed_types'] = 'xls|xlsx';

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('excel')) {
                $error = array('error' => $this->upload->display_errors());
            } else {
                $data = $this->upload->data();

                error_reporting(E_ALL);
                date_default_timezone_set('Asia/Jakarta');

                include './assets/phpexcel/Classes/PHPExcel/IOFactory.php';

                $inputFileName = './assets/excel/' . $data['file_name'];
                $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
                $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

                $index = 0;
                foreach ($sheetData as $key => $value) {
                    if ($key != 1) {
                        $check = $this->Master_model->check_email_pegawai($value['C']);

                        if ($check != 1) {
                            $resultData[$index]['nip'] = $value['A'];
                            $resultData[$index]['nama'] = $value['B'];
                            $resultData[$index]['email'] = $value['C'];
                            $resultData[$index]['jabatan'] = $value['D'];
                        }
                    }
                    $index++;
                }

                unlink('./assets/excel/' . $data['file_name']);

                if (count($resultData) != 0) {
                    $result = $this->Master_model->insert_batch_pegawai($resultData);
                    if ($result > 0) {
                        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Pegawai has been imported!</div>');
                        redirect('master/pegawai');
                        echo json_encode($resultData);
                        die;
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Import failed!</div>');
                    redirect('master/pegawai');
                }
            }
        }
    }

    public function details_mitra($id_mitra)
    {
        $data['title'] = 'Details Mitra';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // JOIN ke tabel kode_kecamatan untuk ambil nama kecamatan
        $this->db->select('mitra.*, kode_kecamatan.nama AS nama_kecamatan, kode_keldes.nama AS nama_desa');
        $this->db->from('mitra');
        $this->db->join('kode_kecamatan', 'mitra.kecamatan = kode_kecamatan.kode', 'left');
        $this->db->join('kode_keldes', 'kode_keldes.kode = CONCAT(mitra.kecamatan, mitra.desa)', 'left');
        $this->db->where('mitra.id_mitra', $id_mitra);
        $data['mitra'] = $this->db->get()->row_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('master/details-mitra', $data);
        $this->load->view('template/footer');
    }


    public function editmitra($id_mitra)
    {
        $data['title'] = 'Edit Mitra';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['mitra'] = $this->db->get_where('mitra', ['id_mitra' => $id_mitra])->row_array();
        $data['kode_kecamatan'] = $this->db->get('kode_kecamatan')->result_array();


        $kode_kec = $data['mitra']['kecamatan']; // Ambil kode kecamatan

        // Ambil daftar desa yang kode-nya diawali dengan kode kecamatan (contoh: 3300XX)
        $this->db->like('kode', $kode_kec, 'after');
        $data['kode_keldes'] = $this->db->get('kode_keldes')->result_array();


        $kode_desa_lengkap = $data['mitra']['kecamatan'] . $data['mitra']['desa'];

        $this->db->where('kode', $kode_desa_lengkap);
        $desa = $this->db->get('kode_keldes')->row_array();

        $data['nama_desa'] = $desa ? $desa['nama'] : 'Desa tidak ditemukan';

        $this->form_validation->set_rules('nik', 'NIK', 'required|trim');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('posisi', 'Posisi', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim');
        $this->form_validation->set_rules('kecamatan', 'Kode Kecamatan', 'required|trim');
        $this->form_validation->set_rules('desa', 'Kode Desa', 'required|trim');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');
        $this->form_validation->set_rules('jk', 'Jenis Kelamin', 'required|trim');
        $this->form_validation->set_rules('no_hp', 'No. HP', 'required|trim');
        $this->form_validation->set_rules('sobat_id', 'Sobat ID', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('master/edit-mitra', $data);
            $this->load->view('template/footer');
        } else {
            $data = [
                'nik' => $this->input->post('nik'),
                'nama' => $this->input->post('nama'),
                'posisi' => $this->input->post('posisi'),
                'email' => $this->input->post('email'),
                'kecamatan' => $this->input->post('kecamatan'),
                'desa' => $this->input->post('desa'),
                'alamat' => $this->input->post('alamat'),
                'jk' => $this->input->post('jk'),
                'no_hp' => $this->input->post('no_hp'),
                'sobat_id' => $this->input->post('sobat_id')
            ];

            $this->db->set($data);
            $this->db->where('id_mitra', $id_mitra);
            $this->db->update('mitra');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Mitra has been updated!</div>');
            redirect('master/mitra');
        }
    }

    function details_kegiatan_mitra($id_mitra)
    {
        $data['title'] = 'Details Kegiatan';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $sql = "SELECT all_kegiatan_pencacah.*, kegiatan.* FROM all_kegiatan_pencacah INNER JOIN kegiatan ON all_kegiatan_pencacah.kegiatan_id = kegiatan.id WHERE all_kegiatan_pencacah.id_mitra = $id_mitra";

        $data['details'] = $this->db->query($sql)->result_array();
        $data['id_mitra'] = $id_mitra;

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('master/details-kegiatan-mitra', $data);
        $this->load->view('template/footer');
    }

    function deletemitra($id_mitra)
    {

        $query = "SELECT email FROM mitra WHERE id_mitra = $id_mitra";
        $email = IMPLODE($this->db->query($query)->row_array());
        $this->Master_model->deletemitrafromuser($email);

        $this->Master_model->deletemitra($id_mitra);

        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Mitra has been deleted!</div>');
        redirect('master/mitra');
    }

    public function deactivated($id_mitra)
    {
        $this->Master_model->deactivated($id_mitra);
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Mitra has been deactivated!</div>');
        redirect('master/mitra');
    }

    public function activated($id_mitra)
    {
        $this->Master_model->activated($id_mitra);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Mitra has been activated!</div>');
        redirect('master/mitra');
    }

    public function pegawai()
    {
        $data['title'] = 'Data Pegawai';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['pegawai'] = $this->db->get('pegawai')->result_array();

        $this->form_validation->set_rules('nip', 'NIP', 'required|trim');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('master/pegawai', $data);
            $this->load->view('template/footer');
        } else {
            $data = [
                'nip' => $this->input->post('nip'),
                'nama' => $this->input->post('nama'),
                'email' => $this->input->post('email'),
                'jabatan' => $this->input->post('jabatan')
            ];

            $this->db->insert('pegawai', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New pegawai added!</div>');
            redirect('master/pegawai');
        }
    }

    public function editpegawai($id_peg)
    {
        $data['title'] = 'Edit Pegawai';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['pegawai'] = $this->db->get_where('pegawai', ['id_peg' => $id_peg])->row_array();

        $this->form_validation->set_rules('nip', 'NIP', 'required|trim');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('master/edit-pegawai', $data);
            $this->load->view('template/footer');
        } else {
            $data = [
                'nip' => $this->input->post('nip'),
                'nama' => $this->input->post('nama'),
                'email' => $this->input->post('email'),
                'jabatan' => $this->input->post('jabatan')
            ];

            $this->db->set($data);
            $this->db->where('id_peg', $id_peg);
            $this->db->update('pegawai');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Pegawai has been updated!</div>');
            redirect('master/pegawai');
        }
    }

    function deletepegawai($id_peg)
    {
        $query = "SELECT email FROM pegawai WHERE id_peg = $id_peg";
        $email = IMPLODE($this->db->query($query)->row_array());
        $this->Master_model->deletepegawaifromuser($email);
        $this->Master_model->deletepegawai($id_peg);

        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Pegawai has been deleted!</div>');
        redirect('master/pegawai');
    }

}
