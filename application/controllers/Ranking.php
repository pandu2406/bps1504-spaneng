<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ranking extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Ranking_model');
    }

    public function index()
    {
        $data['title'] = 'Ranking';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('ranking/index', $data);
        $this->load->view('template/footer');
        show_404();
    }


    public function kriteria()
    {
        $data['title'] = 'Data Kriteria';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $sql_kriteria = "SELECT * FROM kriteria ORDER BY prioritas ASC";
        $data['kriteria'] = $this->db->query($sql_kriteria)->result_array();

        $data['jumlahkriteria'] = $this->db->get('kriteria')->num_rows();

        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('prioritas', 'Prioritas', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('ranking/kriteria', $data);
            $this->load->view('template/footer');
        } else {
            $data = [
                'nama' => $this->input->post('nama'),
                'prioritas' => $this->input->post('prioritas'),
                'bobot' => 1
            ];


            $this->db->insert('kriteria', $data);

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New kriteria added!</div>');
            redirect('ranking/kriteria');
        }
    }

    public function editkriteria($id)
    {
        $data['title'] = 'Edit Kriteria';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['kriteria'] = $this->db->get_where('kriteria', ['id' => $id])->row_array();

        $data['jumlahkriteria'] = $this->db->get('kriteria')->num_rows();

        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('prioritas', 'Prioritas', 'required|trim');
        $this->form_validation->set_rules('bobot', 'Bobot', 'required|trim|numeric');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('ranking/edit-kriteria', $data);
            $this->load->view('template/footer');
        } else {


            $nama = $this->input->post('nama');
            $prioritas = $this->input->post('prioritas');
            $bobot = $this->input->post('bobot');

            $this->db->set('nama', $nama);
            $this->db->set('prioritas', $prioritas);
            $this->db->set('bobot', $bobot);

            $this->db->where('id', $id);
            $this->db->update('kriteria');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Kriteria has been updated!</div>');
            redirect('ranking/kriteria');
        }
    }

    function deletekriteria($id)
    {
        $this->Ranking_model->deletekriteria($id);
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Kriteria has been deleted!</div>');
        redirect('ranking/kriteria');
    }

    function hitung_bobot_kriteria($prioritas)
    {

        $jumlah_kriteria = $this->db->get('kriteria')->num_rows();
        $a = 0;
        for ($i = $prioritas; $i <= $jumlah_kriteria; $i++) {
            $a = $a + 1 / $i;
        }

        $bobot = $a / $jumlah_kriteria;

        $this->db->set('bobot', $bobot);
        $this->db->where('prioritas', $prioritas);
        $this->db->update('kriteria');

        redirect('ranking/kriteria');
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Bobot Kriteria Changed!</div>');
    }

    function subkriteria()
    {
        $data['title'] = 'Data Subkriteria';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $sql_subkriteria = "SELECT * FROM subkriteria ORDER BY prioritas ASC";
        $data['subkriteria'] = $this->db->query($sql_subkriteria)->result_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('ranking/subkriteria', $data);
        $this->load->view('template/footer');
    }

    function hitung_bobot_subkriteria($prioritas)
    {

        $jumlah_subkriteria = $this->db->get('subkriteria')->num_rows();
        $a = 0;
        for ($i = $prioritas; $i <= $jumlah_subkriteria; $i++) {
            $a = $a + 1 / $i;
        }

        $bobot = $a / $jumlah_subkriteria;

        $this->db->set('bobot', $bobot);
        $this->db->where('prioritas', $prioritas);
        $this->db->update('subkriteria');

        redirect('ranking/subkriteria');
    }

    function pilih_kegiatan()
    {
        $data['title'] = 'Penghitungan';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $sql = "SELECT * FROM kegiatan ORDER BY finish DESC";

        $data['kegiatan'] = $this->db->query($sql)->result_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('ranking/pilih-kegiatan', $data);
        $this->load->view('template/footer');
    }

    function pilih_kegiatan_nilai_akhir()
    {
        $data['title'] = 'Ranking Mitra';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['kegiatan'] = $this->db->query("SELECT * FROM kegiatan ORDER BY finish DESC;")->result_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('ranking/pilih-kegiatan-nilai-akhir', $data);
        $this->load->view('template/footer');
    }

    function data_awal($kegiatan_id)
    {
        $data['title'] = 'Perhitungan';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $k_pencacah = "SELECT k_pencacah FROM kegiatan WHERE id = $kegiatan_id";
        $result_k_pencacah = implode($this->db->query($k_pencacah)->row_array());

        $jumlah_kriteria = $this->db->get('kriteria')->num_rows();

        $jumlah_penilaian = ((int) $result_k_pencacah) * $jumlah_kriteria;

        $all_kegiatan_pencacah_id = "SELECT id FROM all_kegiatan_pencacah WHERE kegiatan_id = $kegiatan_id";

        $sql_jumlah_penilaian_sementara = "SELECT * FROM all_penilaian WHERE all_kegiatan_pencacah_id IN ($all_kegiatan_pencacah_id)";

        $jumlah_penilaian_sementara = $this->db->query($sql_jumlah_penilaian_sementara)->num_rows();

        // var_dump($jumlah_penilaian);
        // die;

        if ($jumlah_penilaian_sementara == $jumlah_penilaian) {

            $data['kegiatan_id'] = $kegiatan_id;

            $sql_kriteria = "SELECT * FROM kriteria ORDER BY id";
            $data['kriteria'] = $this->db->query($sql_kriteria)->result();

            $sql_id_mitra = "SELECT all_kegiatan_pencacah.id_mitra, mitra.nama FROM all_kegiatan_pencacah JOIN mitra ON all_kegiatan_pencacah.id_mitra = mitra.id_mitra WHERE kegiatan_id = $kegiatan_id ORDER BY id_mitra";
            $data['id_mitra'] = $this->db->query($sql_id_mitra)->result();

            $hasil = $this->Ranking_model->data_awal($kegiatan_id);
            $data['rekap'] = $hasil['data'];



            // var_dump($data['rekap']);
            // die;

            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('ranking/hitung-data-awal', $data);
            $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Selesaikan penilaian terlebih dahulu!</div>');
            redirect('ranking/pilih_kegiatan');
        }
    }

    public function normalized($kegiatan_id)
{
    $data['title'] = 'Perhitungan';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

    // Ambil jumlah pencacah
    $k_pencacah = "SELECT k_pencacah FROM kegiatan WHERE id = $kegiatan_id";
    $result_k_pencacah = (int) implode($this->db->query($k_pencacah)->row_array());

    // Hitung jumlah kriteria
    $jumlah_kriteria = $this->db->get('kriteria')->num_rows();

    // Hitung total penilaian yang seharusnya
    $jumlah_penilaian = $result_k_pencacah * $jumlah_kriteria;

    // Ambil ID all_kegiatan_pencacah
    $subquery_id = "SELECT id FROM all_kegiatan_pencacah WHERE kegiatan_id = $kegiatan_id";

    // Hitung jumlah penilaian yang sudah ada
    $sql_jumlah_penilaian_sementara = "SELECT * FROM all_penilaian WHERE all_kegiatan_pencacah_id IN ($subquery_id)";
    $jumlah_penilaian_sementara = $this->db->query($sql_jumlah_penilaian_sementara)->num_rows();

    // Jika jumlah penilaian sudah sesuai
    if ($jumlah_penilaian_sementara == $jumlah_penilaian) {

        $data['kegiatan_id'] = $kegiatan_id;

        // Ambil data kriteria urut berdasarkan prioritas
        $data['kriteria'] = $this->db->query("SELECT * FROM kriteria ORDER BY prioritas ASC")->result();

        // Ambil data mitra
        $sql_id_mitra = "SELECT all_kegiatan_pencacah.id_mitra, mitra.nama 
                         FROM all_kegiatan_pencacah 
                         JOIN mitra ON all_kegiatan_pencacah.id_mitra = mitra.id_mitra 
                         WHERE kegiatan_id = $kegiatan_id 
                         ORDER BY id_mitra";
        $data['id_mitra'] = $this->db->query($sql_id_mitra)->result();

        // Ambil data penilaian ter-normalisasi dari model
        $hasil = $this->Ranking_model->normalized($kegiatan_id);
        $data['rekap'] = $hasil['data']; // ['id_mitra', 'kriteria_id', 'nilai_asli', 'normalized']

        // Load view
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('ranking/hitung-normalized', $data);
        $this->load->view('template/footer');

    } else {
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Selesaikan penilaian terlebih dahulu!</div>');
        redirect('ranking/pilih_kegiatan');
    }
}


public function utility($kegiatan_id)
{
    $data['title'] = 'Perhitungan';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

    // Ambil jumlah pencacah dari kegiatan
    $k_pencacah = $this->db->query("SELECT k_pencacah FROM kegiatan WHERE id = $kegiatan_id")->row('k_pencacah');

    // Hitung jumlah kriteria
    $jumlah_kriteria = $this->db->get('kriteria')->num_rows();
    $data['jumlah_kriteria'] = $jumlah_kriteria;
    $data['kegiatan_id'] = $kegiatan_id;

    // Hitung total penilaian yang seharusnya
    $jumlah_penilaian = ((int) $k_pencacah) * $jumlah_kriteria;

    // Hitung jumlah penilaian yang sudah dilakukan
    $subquery = "SELECT id FROM all_kegiatan_pencacah WHERE kegiatan_id = $kegiatan_id";
    $jumlah_penilaian_terisi = $this->db->query("SELECT * FROM all_penilaian WHERE all_kegiatan_pencacah_id IN ($subquery)")->num_rows();

    if ($jumlah_penilaian_terisi == $jumlah_penilaian) {
        // Ambil data kriteria (untuk header tabel view)
        $data['kriteria'] = $this->db->query("SELECT * FROM kriteria ORDER BY prioritas ASC")->result();

        // Ambil daftar mitra pada kegiatan ini
        $data['id_mitra'] = $this->db->query("
            SELECT DISTINCT all_kegiatan_pencacah.id_mitra, mitra.nama
            FROM all_kegiatan_pencacah
            JOIN mitra ON all_kegiatan_pencacah.id_mitra = mitra.id_mitra
            WHERE all_kegiatan_pencacah.kegiatan_id = $kegiatan_id
            ORDER BY all_kegiatan_pencacah.id_mitra
        ")->result();

        // Ambil hasil utility dari model
        $hasil = $this->Ranking_model->utility($kegiatan_id);
        $data['rekap'] = $hasil['data'];

        // Load views
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('ranking/hitung-utility', $data);
        $this->load->view('template/footer');
    } else {
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Selesaikan penilaian terlebih dahulu!</div>');
        redirect('ranking/pilih_kegiatan');
    }
}


    public function total($kegiatan_id)
    {
        // Set judul halaman
        $data['title'] = 'Perhitungan';

        // Ambil data user dari session
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email')
        ])->row_array();

        // Ambil jumlah pencacah dari kegiatan
        $kegiatan = $this->db->get_where('kegiatan', ['id' => $kegiatan_id])->row();
        if (!$kegiatan) {
            show_error("Kegiatan tidak ditemukan.");
        }

        $k_pencacah = (int) $kegiatan->k_pencacah;

        // Hitung jumlah kriteria dan penilaian yang dibutuhkan
        $jumlah_kriteria = $this->db->get('kriteria')->num_rows();
        $jumlah_penilaian = $k_pencacah * $jumlah_kriteria;

        $data['jumlah_kriteria'] = $jumlah_kriteria;
        $data['kegiatan_id'] = $kegiatan_id;

        // Hitung jumlah penilaian yang sudah masuk
        $subquery = "SELECT id FROM all_kegiatan_pencacah WHERE kegiatan_id = $kegiatan_id";
        $jumlah_penilaian_terisi = $this->db->query("
            SELECT COUNT(*) AS total 
            FROM all_penilaian 
            WHERE all_kegiatan_pencacah_id IN ($subquery)
        ")->row('total');

        // Cek apakah semua penilaian sudah terisi
        if ($jumlah_penilaian_terisi == $jumlah_penilaian) {

            // Ambil kriteria (untuk header tabel di view)
            $data['kriteria'] = $this->db->query("
                SELECT * FROM kriteria ORDER BY prioritas ASC
            ")->result();

            // Ambil data utility yang sudah termasuk mitra
            $hasil = $this->Ranking_model->total($kegiatan_id);

            // Simpan ke variabel untuk view
            $data['rekap'] = $hasil['data']; // format: array of stdClass (id_mitra, nama, bobot[])

            // Load view
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('ranking/hitung-total', $data);
            $this->load->view('template/footer');

        } else {
            // Jika penilaian belum lengkap
            $this->session->set_flashdata('message', '
                <div class="alert alert-danger" role="alert">
                    Selesaikan penilaian terlebih dahulu!
                </div>
            ');
            redirect('ranking/pilih_kegiatan');
        }
    }

    public function nilai_akhir($kegiatan_id)
{
    $data['title'] = 'Perhitungan';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

    // Ambil jumlah pencacah dari kegiatan
    $k_pencacah = "SELECT k_pencacah FROM kegiatan WHERE id = $kegiatan_id";
    $result_k_pencacah = implode($this->db->query($k_pencacah)->row_array());

    // Hitung jumlah total penilaian yang diharapkan
    $jumlah_kriteria = $this->db->get('kriteria')->num_rows();
    $jumlah_penilaian = ((int) $result_k_pencacah) * $jumlah_kriteria;

    // Hitung jumlah penilaian yang sudah ada
    $all_kegiatan_pencacah_id = "SELECT id FROM all_kegiatan_pencacah WHERE kegiatan_id = $kegiatan_id";
    $sql_jumlah_penilaian_sementara = "SELECT * FROM all_penilaian WHERE all_kegiatan_pencacah_id IN ($all_kegiatan_pencacah_id)";
    $jumlah_penilaian_sementara = $this->db->query($sql_jumlah_penilaian_sementara)->num_rows();

    // Cek apakah penilaian sudah lengkap
    if ($jumlah_penilaian_sementara == $jumlah_penilaian) {

        // Ambil data utility untuk rekap
        $hasil = $this->Ranking_model->total($kegiatan_id);
        $data['rekap'] = $hasil['data'];

        // Ambil data ranking terurut total tertinggi
        $data['hq'] = $this->Ranking_model->totalakhir($kegiatan_id);

        $data['kegiatan_id'] = $kegiatan_id;

        // Load view
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('ranking/hitung-nilai-akhir', $data);
        $this->load->view('template/footer');

    } else {
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Selesaikan penilaian terlebih dahulu!</div>');
        redirect('ranking/pilih_kegiatan');
    }
}


public function nilai_akhir_ranking($kegiatan_id)
{
    $data['title'] = 'Perhitungan';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

    // Ambil data kegiatan lengkap (bukan hanya ID)
    $data['kegiatan'] = $this->db->get_where('kegiatan', ['id' => $kegiatan_id])->row();

    // Ambil jumlah pencacah dari kegiatan
    $result_k_pencacah = $data['kegiatan']->k_pencacah ?? 0;

    // Hitung jumlah total penilaian yang diharapkan
    $jumlah_kriteria = $this->db->get('kriteria')->num_rows();
    $jumlah_penilaian = ((int) $result_k_pencacah) * $jumlah_kriteria;

    // Hitung jumlah penilaian yang sudah ada
    $all_kegiatan_pencacah_id = "SELECT id FROM all_kegiatan_pencacah WHERE kegiatan_id = $kegiatan_id";
    $sql_jumlah_penilaian_sementara = "SELECT * FROM all_penilaian WHERE all_kegiatan_pencacah_id IN ($all_kegiatan_pencacah_id)";
    $jumlah_penilaian_sementara = $this->db->query($sql_jumlah_penilaian_sementara)->num_rows();

    // Cek apakah penilaian sudah lengkap
    if ($jumlah_penilaian_sementara == $jumlah_penilaian) {

        // Ambil data utility untuk rekap
        $hasil = $this->Ranking_model->total($kegiatan_id);
        $data['rekap'] = $hasil['data'];

        // Ambil data ranking
        $data['hq'] = $this->Ranking_model->totalakhir($kegiatan_id);

        // Tetap simpan kegiatan_id jika diperlukan
        $data['kegiatan_id'] = $kegiatan_id;

        // Load view
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('ranking/ranking', $data);
        $this->load->view('template/footer');

    } else {
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Selesaikan penilaian terlebih dahulu!</div>');
        redirect('ranking/pilih_kegiatan');
    }
}


    function cek_progress($kegiatan_id)
    {
        $data['title'] = 'Progress Penilaian';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $query = ("SELECT 
        COALESCE(pegawai.nama, mitra_pengawas.nama) AS nmpegawai,
        mitra.nama AS nmmitra,
        COUNT(all_penilaian.kriteria_id) AS progress
            FROM all_kegiatan_pencacah
            LEFT JOIN mitra ON all_kegiatan_pencacah.id_mitra = mitra.id_mitra
            LEFT JOIN pegawai ON all_kegiatan_pencacah.id_pengawas = pegawai.id_peg
            LEFT JOIN mitra AS mitra_pengawas ON all_kegiatan_pencacah.id_pengawas = mitra_pengawas.id_mitra
            LEFT JOIN all_penilaian ON all_penilaian.all_kegiatan_pencacah_id = all_kegiatan_pencacah.id
            WHERE kegiatan_id = $kegiatan_id
            GROUP BY nmpegawai, nmmitra
            ORDER BY nmpegawai
        ");

        $data['progress'] = $this->db->query($query)->result_array();
        $data['nama_kegiatan'] = $this->db->query("SELECT nama FROM kegiatan WHERE id=$kegiatan_id")->row_array();
        $data['jumlah_kriteria'] = $this->db->get('kriteria')->num_rows();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('ranking/progress', $data);
        $this->load->view('template/footer');
    }
}
