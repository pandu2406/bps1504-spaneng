<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

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
        $this->output->cache(1);
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
        $this->output->cache(1);
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
        $this->output->cache(1);
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
        $this->output->cache(1);
        $data['title'] = 'Penghitungan';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // Filter Logic
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        $where_clause = "";
        if (!empty($bulan) && !empty($tahun)) {
            $where_clause = "WHERE FROM_UNIXTIME(k.finish, '%m') = '$bulan' AND FROM_UNIXTIME(k.finish, '%Y') = '$tahun'";
        } elseif (!empty($tahun)) {
            $where_clause = "WHERE FROM_UNIXTIME(k.finish, '%Y') = '$tahun'";
        }

        // OPTIMIZED QUERY: Calculate Target vs Realization with Filter
        $sql = "SELECT k.*,
            (k.k_pencacah * (SELECT COUNT(*) FROM kriteria)) AS target_penilaian,
            (SELECT COUNT(*) FROM all_penilaian ap
             JOIN all_kegiatan_pencacah akp ON ap.all_kegiatan_pencacah_id = akp.id
             WHERE akp.kegiatan_id = k.id) AS realisasi_penilaian
        FROM kegiatan k
        $where_clause
        ORDER BY k.finish DESC";

        $data['kegiatan'] = $this->db->query($sql)->result_array();
        $data['selected_bulan'] = $bulan;
        $data['selected_tahun'] = $tahun;

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

        // Filter Logic
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        $where_clause = "";
        if (!empty($bulan) && !empty($tahun)) {
            $where_clause = "WHERE FROM_UNIXTIME(k.finish, '%m') = '$bulan' AND FROM_UNIXTIME(k.finish, '%Y') = '$tahun'";
        } elseif (!empty($tahun)) {
            $where_clause = "WHERE FROM_UNIXTIME(k.finish, '%Y') = '$tahun'";
        }

        // OPTIMIZED QUERY: Calculate Target vs Realization with Filter
        $sql = "SELECT k.*,
            (k.k_pencacah * (SELECT COUNT(*) FROM kriteria)) AS target_penilaian,
            (SELECT COUNT(*) FROM all_penilaian ap
             JOIN all_kegiatan_pencacah akp ON ap.all_kegiatan_pencacah_id = akp.id
             WHERE akp.kegiatan_id = k.id) AS realisasi_penilaian
        FROM kegiatan k
        $where_clause
        ORDER BY k.finish DESC";

        $data['kegiatan'] = $this->db->query($sql)->result_array();
        $data['selected_bulan'] = $bulan;
        $data['selected_tahun'] = $tahun;

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('ranking/pilih-kegiatan-nilai-akhir', $data);
        $this->load->view('template/footer');
    }

    public function data_awal($kegiatan_id)
    {
        $data['title'] = 'Perhitungan';
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email')
        ])->row_array();

        // Ambil jumlah pencacah dari kegiatan
        $kegiatan = $this->db->get_where('kegiatan', ['id' => $kegiatan_id])->row_array();
        $jumlah_pencacah = (int) $kegiatan['k_pencacah'];

        // Hitung jumlah kriteria
        $jumlah_kriteria = $this->db->get('kriteria')->num_rows();

        // Hitung total penilaian pencacah yang diharapkan
        $jumlah_penilaian_diharapkan = $jumlah_pencacah * $jumlah_kriteria;

        // Hitung total penilaian pencacah yang sudah ada
        $subquery_pencacah = "SELECT id FROM all_kegiatan_pencacah WHERE kegiatan_id = $kegiatan_id";
        $jumlah_penilaian_pencacah = $this->db
            ->query("SELECT * FROM all_penilaian WHERE all_kegiatan_pencacah_id IN ($subquery_pencacah)")
            ->num_rows();

        // Validasi hanya penilaian pencacah
        if ($jumlah_penilaian_pencacah == $jumlah_penilaian_diharapkan) {
            $data['kegiatan_id'] = $kegiatan_id;

            // Ambil semua kriteria
            $data['kriteria'] = $this->db->query("SELECT * FROM kriteria ORDER BY id")->result();

            // Ambil daftar mitra pencacah
            $sql_mitra_pencacah = "
            SELECT akp.id_mitra, m.nama 
            FROM all_kegiatan_pencacah akp
            JOIN mitra m ON akp.id_mitra = m.id_mitra
            WHERE akp.kegiatan_id = $kegiatan_id
        ";

            // Ambil daftar mitra pengawas
            $sql_mitra_pengawas = "
            SELECT akg.id_pengawas AS id_mitra, m.nama 
            FROM all_kegiatan_pengawas akg
            JOIN mitra m ON akg.id_pengawas = m.id_mitra
            WHERE akg.kegiatan_id = $kegiatan_id
        ";

            // Gabungkan mitra pencacah dan pengawas
            $data['id_mitra'] = array_merge(
                $this->db->query($sql_mitra_pencacah)->result(),
                $this->db->query($sql_mitra_pengawas)->result()
            );

            // Ambil data penilaian (pencacah + pengawas)
            $hasil = $this->Ranking_model->data_awal($kegiatan_id);
            $data['rekap'] = $hasil['data'];

            // Tampilkan view
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('ranking/hitung-data-awal', $data);
            $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Selesaikan penilaian pencacah terlebih dahulu!</div>');
            redirect('ranking/pilih_kegiatan');
        }
    }

    public function normalized($kegiatan_id)
    {
        $data['title'] = 'Perhitungan';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // Ambil jumlah pencacah dari kegiatan
        $kegiatan = $this->db->get_where('kegiatan', ['id' => $kegiatan_id])->row_array();
        $jumlah_pencacah = (int) $kegiatan['k_pencacah'];

        // Hitung jumlah kriteria
        $jumlah_kriteria = $this->db->get('kriteria')->num_rows();

        // Hitung total penilaian pencacah yang diharapkan
        $jumlah_penilaian_diharapkan = $jumlah_pencacah * $jumlah_kriteria;

        // Hitung jumlah penilaian pencacah yang sudah ada
        $subquery_pencacah = "SELECT id FROM all_kegiatan_pencacah WHERE kegiatan_id = $kegiatan_id";
        $jumlah_penilaian_pencacah = $this->db
            ->query("SELECT * FROM all_penilaian WHERE all_kegiatan_pencacah_id IN ($subquery_pencacah)")
            ->num_rows();

        // Jika penilaian pencacah sudah lengkap
        if ($jumlah_penilaian_pencacah == $jumlah_penilaian_diharapkan) {

            $data['kegiatan_id'] = $kegiatan_id;

            // Ambil kriteria
            $data['kriteria'] = $this->db->query("SELECT * FROM kriteria ORDER BY prioritas ASC")->result();

            // Ambil daftar mitra pencacah
            $sql_mitra_pencacah = "
            SELECT akp.id_mitra, m.nama, 'mitra' AS peran 
            FROM all_kegiatan_pencacah akp
            JOIN mitra m ON akp.id_mitra = m.id_mitra
            WHERE akp.kegiatan_id = $kegiatan_id
        ";

            // Ambil daftar mitra pengawas
            $sql_mitra_pengawas = "
            SELECT akg.id_pengawas AS id_mitra, m.nama, 'pengawas' AS peran 
            FROM all_kegiatan_pengawas akg
            JOIN mitra m ON akg.id_pengawas = m.id_mitra
            WHERE akg.kegiatan_id = $kegiatan_id
        ";

            // Gabungkan pencacah + pengawas
            $data['id_mitra'] = array_merge(
                $this->db->query($sql_mitra_pencacah)->result(),
                $this->db->query($sql_mitra_pengawas)->result()
            );

            // Ambil data normalisasi dari model
            $hasil = $this->Ranking_model->normalized($kegiatan_id);
            $data['rekap'] = $hasil['data'];  // ['id_mitra', 'peran', 'kriteria_id', 'normalized', dst.]

            // Load view
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('ranking/hitung-normalized', $data);
            $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Selesaikan penilaian pencacah terlebih dahulu!</div>');
            redirect('ranking/pilih_kegiatan');
        }
    }


    public function utility($kegiatan_id)
    {
        $data['title'] = 'Perhitungan';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // Ambil jumlah pencacah
        $k_pencacah = $this->db->query("SELECT k_pencacah FROM kegiatan WHERE id = $kegiatan_id")->row('k_pencacah');

        // Hitung jumlah kriteria
        $jumlah_kriteria = $this->db->get('kriteria')->num_rows();
        $data['jumlah_kriteria'] = $jumlah_kriteria;
        $data['kegiatan_id'] = $kegiatan_id;

        // Hitung total penilaian yang diharapkan (pencacah saja)
        $jumlah_penilaian = ((int) $k_pencacah) * $jumlah_kriteria;

        // Hitung jumlah penilaian pencacah yang sudah ada
        $subquery = "SELECT id FROM all_kegiatan_pencacah WHERE kegiatan_id = $kegiatan_id";
        $jumlah_penilaian_terisi = $this->db->query("SELECT * FROM all_penilaian WHERE all_kegiatan_pencacah_id IN ($subquery)")->num_rows();

        if ($jumlah_penilaian_terisi == $jumlah_penilaian) {

            // Ambil kriteria
            $data['kriteria'] = $this->db->query("SELECT * FROM kriteria ORDER BY prioritas ASC")->result();

            // Ambil daftar mitra pencacah
            $sql_pencacah = "
            SELECT akp.id_mitra, m.nama, 'mitra' AS peran
            FROM all_kegiatan_pencacah akp
            JOIN mitra m ON akp.id_mitra = m.id_mitra
            WHERE akp.kegiatan_id = $kegiatan_id
        ";

            // Ambil daftar pengawas
            $sql_pengawas = "
            SELECT akg.id_pengawas AS id_mitra, m.nama, 'pengawas' AS peran
            FROM all_kegiatan_pengawas akg
            JOIN mitra m ON akg.id_pengawas = m.id_mitra
            WHERE akg.kegiatan_id = $kegiatan_id
        ";

            // Gabungkan keduanya
            $data['id_mitra'] = array_merge(
                $this->db->query($sql_pencacah)->result(),
                $this->db->query($sql_pengawas)->result()
            );

            // Ambil data utility
            $hasil = $this->Ranking_model->utility($kegiatan_id);
            $data['rekap'] = $hasil['data'];

            // Tampilkan view
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
            redirect('ranking/pilih_kegiatan_nilai_akhir');
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
            redirect('ranking/pilih_kegiatan_nilai_akhir');
        }
    }


    function cek_progress($kegiatan_id)
    {
        $this->output->cache(1);
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

    public function cetak_excel($id_kegiatan)
    {
        $mitra = $this->Ranking_model->get_mitra_by_kegiatan($id_kegiatan);
        $nilai_list = $this->Ranking_model->totalakhir($id_kegiatan);

        // Mapping nilai akhir berdasarkan id_mitra
        $nilai_map = [];
        foreach ($nilai_list as $n) {
            $nilai_map[$n->id_mitra] = $n->total;
        }

        // Load PHPExcel library
        require(APPPATH . '../assets/phpexcel/Classes/PHPExcel.php');
        require(APPPATH . '../assets/phpexcel/Classes/PHPExcel/Writer/Excel2007.php');

        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle('Mitra Sobat');

        // Header
        $sheet->setCellValue('A1', 'Email');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Posisi Tugas');
        $sheet->setCellValue('D1', 'Nilai');
        $sheet->setCellValue('E1', 'Komentar');

        // Styling Header
        $styleHeader = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => ['rgb' => 'CCCCCC']
            ]
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($styleHeader);

        $row = 2;
        foreach ($mitra as $m) {
            $nilai = $nilai_map[$m->id_mitra] ?? null;
            $skor = '';

            if ($nilai !== null) {
                if ($nilai > 90 && $nilai <= 100) {
                    $skor = 5;
                } elseif ($nilai > 75 && $nilai <= 90) {
                    $skor = 4;
                } elseif ($nilai > 60 && $nilai <= 75) {
                    $skor = 3;
                } elseif ($nilai > 50 && $nilai <= 60) {
                    $skor = 2;
                } elseif ($nilai <= 50) {
                    $skor = 1;
                }
            }

            // Penentuan Posisi Tugas
            $peran = $m->peran ?? '';
            $posisi_id = $m->jeniskegiatan_id ?? null;
            $posisi = '';

            if ($peran == 'pengawas') {
                if ($posisi_id == 1) {
                    $posisi = 'Petugas Pendataan Lapangan (PML Survei)';
                } elseif ($posisi_id == 3) {
                    $posisi = 'Petugas Pendataan Lapangan (PML Sensus)';
                } else {
                    $posisi = 'Pengawas (PML)';
                }
            } elseif ($peran == 'pencacah') {
                if ($posisi_id == 1) {
                    $posisi = 'Petugas Pendataan Lapangan (PPL Survei)';
                } elseif ($posisi_id == 2) {
                    $posisi = 'Petugas Pengolahan Survei';
                } elseif ($posisi_id == 3) {
                    $posisi = 'Petugas Pendataan Lapangan (PPL Sensus)';
                } elseif ($posisi_id == 4) {
                    $posisi = 'Petugas Pengolahan Sensus';
                } else {
                    $posisi = 'Pencacah (PPL)';
                }
            }

            $sheet->setCellValue('A' . $row, $m->email);
            $sheet->setCellValue('B' . $row, $m->nama);
            $sheet->setCellValue('C' . $row, $posisi);
            $sheet->setCellValue('D' . $row, $nilai !== null ? $skor : 'Belum Dinilai');
            $sheet->setCellValue('E' . $row, ''); // Komentar kosong

            $row++;
        }

        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $filename = 'Ranking_Mitra_' . date('Ymd_His') . '.xlsx';

        // Bersihkan output buffer sebelum mengirim header
        ob_end_clean();

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }


}

