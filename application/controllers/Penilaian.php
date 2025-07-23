<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penilaian extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in_user();
        $this->load->model('Penilaian_model');
        $this->load->library('user_agent');
    }

    public function index()
{
    $data['title'] = 'Penilaian PPL';
    $data['user'] = $this->db->get_where('user', [
        'email' => $this->session->userdata('email')
    ])->row_array();

    $email = $data['user']['email'];

    // Ambil nama user (pegawai atau mitra)
    $sql_nama = "
        SELECT nama FROM pegawai WHERE email = '$email'
        UNION
        SELECT nama FROM mitra WHERE email = '$email'
    ";
    $row_nama = $this->db->query($sql_nama)->row_array();
    $data['nama'] = isset($row_nama['nama']) ? $row_nama['nama'] : 'Tidak diketahui';

    // Ambil ID pegawai atau mitra (dikonversi ke alias id_peg)
    $sql_id_peg = "
        SELECT id_peg FROM pegawai WHERE email = '$email'
        UNION
        SELECT id_mitra as id_peg FROM mitra WHERE email = '$email'
    ";
    $row_id = $this->db->query($sql_id_peg)->row_array();
    $id_peg = isset($row_id['id_peg']) ? $row_id['id_peg'] : null;
    $data['id_peg'] = $id_peg;

    // Cegah query kosong jika id_peg tidak ditemukan
    if (!$id_peg) {
        show_error('ID pegawai/mitra tidak ditemukan.');
        return;
    }

    // Ambil kegiatan berdasarkan id_pengawas
    $this->db->select('all_kegiatan_pengawas.*, kegiatan.*');
    $this->db->from('all_kegiatan_pengawas');
    $this->db->join('kegiatan', 'all_kegiatan_pengawas.kegiatan_id = kegiatan.id');
    $this->db->where('all_kegiatan_pengawas.id_pengawas', $id_peg);
    $data['kegiatan'] = $this->db->get()->result_array();

    // Tampilkan ke view
    $this->load->view('template/header', $data);
    $this->load->view('template/sidebar', $data);
    $this->load->view('template/topbar', $data);
    $this->load->view('penilaian/index', $data);
    $this->load->view('template/footer');
}

public function index_pml()
{
    $data['title'] = 'Penilaian PML';
    $data['user'] = $this->db->get_where('user', [
        'email' => $this->session->userdata('email')
    ])->row_array();

    $email = $data['user']['email'];

    // Tampilkan langsung nama Admin
    $data['nama'] = 'Admin';

    // Coba ambil ID pegawai/mitra, jika ada
    $sql_id_peg = "
        SELECT id_peg FROM pegawai WHERE email = '$email'
        UNION
        SELECT id_mitra as id_peg FROM mitra WHERE email = '$email'
    ";
    $row_id = $this->db->query($sql_id_peg)->row_array();
    $data['id_peg'] = isset($row_id['id_peg']) ? $row_id['id_peg'] : null;

    if (!$data['id_peg']) {
        // Jika admin, ambil semua kegiatan
        $data['kegiatan'] = $this->db->get('kegiatan')->result_array();
    } else {
        // Jika PML, ambil berdasarkan kegiatan di mana dia jadi pengawas
        $this->db->select('all_kegiatan_pengawas.*, kegiatan.*');
        $this->db->from('all_kegiatan_pengawas');
        $this->db->join('kegiatan', 'all_kegiatan_pengawas.kegiatan_id = kegiatan.id');
        $this->db->where('all_kegiatan_pengawas.id_pengawas', $data['id_peg']);
        $data['kegiatan'] = $this->db->get()->result_array();
    }

    $this->load->view('template/header', $data);
    $this->load->view('template/sidebar', $data);
    $this->load->view('template/topbar', $data);
    $this->load->view('penilaian/index_pml', $data);
    $this->load->view('template/footer');
}

public function daftar_pencacah($kegiatan_id, $id_peg)
{
    $data['title'] = 'Daftar Pencacah';
    $data['user'] = $this->db->get_where('user', [
        'email' => $this->session->userdata('email')
    ])->row_array();

    // Simpan ID kegiatan agar bisa dipakai di view
    $data['kegiatan_id'] = $kegiatan_id;

    // Simpan ID pegawai penilai
    $data['id_peg'] = $id_peg;

    // Ambil data kegiatan pencacah yang diawasi oleh pegawai tertentu
    $sql = "SELECT all_kegiatan_pencacah.*, mitra.nama, mitra.nik 
            FROM all_kegiatan_pencacah 
            INNER JOIN mitra ON all_kegiatan_pencacah.id_mitra = mitra.id_mitra 
            WHERE all_kegiatan_pencacah.kegiatan_id = $kegiatan_id 
              AND all_kegiatan_pencacah.id_pengawas = $id_peg";
    $data['kegiatan'] = $this->db->query($sql)->result_array();

    // Nama kegiatan
    $data['nama_kegiatan'] = $this->db->get_where('kegiatan', ['id' => $kegiatan_id])->row_array();

    // Load view
    $this->load->view('template/header', $data);
    $this->load->view('template/sidebar', $data);
    $this->load->view('template/topbar', $data);
    $this->load->view('penilaian/daftar-pencacah', $data);
    $this->load->view('template/footer');
}

    public function daftar_pengawas($kegiatan_id)
{
    $data['title'] = 'Daftar Pengawas';
    $data['user']  = $this->db
                       ->get_where('user', ['email' => $this->session->userdata('email')])
                       ->row_array();

    // Ambil semua pengawas untuk kegiatan ini
    $sql = "
        SELECT 
            akp.*, 
            m.nama, 
            m.nik
        FROM all_kegiatan_pengawas akp
        JOIN mitra m 
          ON akp.id_pengawas = m.id_mitra
        WHERE akp.kegiatan_id = ?
    ";
    $data['pengawas'] = $this->db
                             ->query($sql, [$kegiatan_id])
                             ->result_array();

    // Nama kegiatan untuk header
    $data['nama_kegiatan'] = $this->db
                                 ->get_where('kegiatan', ['id' => $kegiatan_id])
                                 ->row_array();

    // Buat link kembali
    $data['kegiatan_id'] = $kegiatan_id;

    // Load view
    $this->load->view('template/header',  $data);
    $this->load->view('template/sidebar', $data);
    $this->load->view('template/topbar',  $data);
    $this->load->view('penilaian/daftar-pengawas', $data);
    $this->load->view('template/footer');
}

public function isi_nilai($kegiatan_id, $id_peg, $id_target, $peran = 'mitra')
{
    $this->load->helper('pandu_helper');

    $data['title'] = 'Isi Nilai';
    $data['user'] = $this->db->get_where('user', [
        'email' => $this->session->userdata('email')
    ])->row_array();

    $data['kegiatan'] = $this->db->get_where('kegiatan', ['id' => $kegiatan_id])->row_array();
    if (!$data['kegiatan']) {
        show_404();
    }

    $data['id_peg'] = ($id_peg === 'admin') ? null : $id_peg;

    $data['target'] = $this->db->get_where('mitra', ['id_mitra' => $id_target])->row_array();
    if (!$data['target']) {
        show_error("Data mitra tidak ditemukan untuk ID: $id_target");
        return;
    }

    $data['peran'] = $peran;

    // Ambil data kegiatan berdasarkan peran
    if ($peran == 'mitra') {
        $where = ['kegiatan_id' => $kegiatan_id, 'id_mitra' => $id_target];
        $data['all_kegiatan_pencacah'] = $this->db->get_where('all_kegiatan_pencacah', $where)->row_array();
        if (!$data['all_kegiatan_pencacah']) {
            show_error("Data kegiatan pencacah tidak ditemukan.");
            return;
        }
    } elseif ($peran == 'pengawas') {
        $where = ['kegiatan_id' => $kegiatan_id, 'id_pengawas' => $id_target];
        $data['all_kegiatan_pengawas'] = $this->db->get_where('all_kegiatan_pencacah', $where)->row_array();
        if (!$data['all_kegiatan_pengawas']) {
            show_error("Data kegiatan pengawas tidak ditemukan.");
            return;
        }
    } else {
        show_error("Peran tidak dikenali: $peran");
        return;
    }

    $data['kriteria'] = $this->db->order_by('id', 'ASC')->get('kriteria')->result_array();

    $this->load->view('template/header', $data);
    $this->load->view('template/sidebar', $data);
    $this->load->view('template/topbar', $data);
    $this->load->view('penilaian/isi-nilai', $data);
    $this->load->view('template/footer');
}


    public function changenilai()
    {

        $this->load->helper('pandu_helper');

        $all_kegiatan_pencacah_id = $this->input->post('allkegiatanpencacahId');
        $kriteria_id = $this->input->post('kriteriaId');
        $nilai = $this->input->post('nilaiId');
        $t_bobot = $this->input->post('bobotId');

        $data = [
            'all_kegiatan_pencacah_id' => $all_kegiatan_pencacah_id,
            'kriteria_id' => $kriteria_id,
            'nilai' => $nilai
        ];

        $data2 = [
            'all_kegiatan_pencacah_id' => $all_kegiatan_pencacah_id,
            'kriteria_id' => $kriteria_id
        ];

        $result = $this->db->get_where('all_penilaian', $data2);

        if ($result->num_rows() < 1) {
            $this->db->insert('all_penilaian', $data);
        } else {
            $query = "UPDATE all_penilaian SET nilai = $nilai WHERE all_kegiatan_pencacah_id = $all_kegiatan_pencacah_id  AND kriteria_id = $kriteria_id";
            $this->db->query($query);
        }
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Nilai changed!</div>');

        // $kegiatan_id = $this->input->post('kegiatanId');
        // $id_mitra = $this->input->post('mitraId');
        // $kriteria_id = $this->input->post('kriteriaId');
        // $nilai = $this->input->post('nilaiId');

        // $data = [
        //     'kegiatan_id' => $kegiatan_id,
        //     'id_mitra' => $id_mitra,
        //     'kriteria_id' => $kriteria_id,
        //     'nilai' => $nilai
        // ];

        // $data2 = [
        //     'kegiatan_id' => $kegiatan_id,
        //     'id_mitra' => $id_mitra,
        //     'kriteria_id' => $kriteria_id
        // ];

        // $result = $this->db->get_where('all_penilaian', $data2);

        // if ($result->num_rows() < 1) {
        //     $this->db->insert('all_penilaian', $data);
        // } else {
        //     $query = "UPDATE all_penilaian SET nilai = $nilai WHERE kegiatan_id = $kegiatan_id AND id_mitra = $id_mitra AND kriteria_id = $kriteria_id";
        //     $this->db->query($query);
        // }
        // $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Nilai changed!</div>');
    }

    public function simpannilai()
{
    $this->load->model('Penilaian_model');

    $peran = $this->input->post('peran'); // mitra atau pengawas
    $all_id = $this->input->post('all_id'); // ID untuk tabel all_kegiatan_pencacah/pengawas
    $nilai_array = $this->input->post('nilai'); // [kriteria_id => nilai]

    if (!$peran || !$all_id || !$nilai_array) {
        $this->session->set_flashdata('message', '<div class="alert alert-danger">Data tidak lengkap.</div>');
        redirect($this->agent->referrer());
        return;
    }

    foreach ($nilai_array as $kriteria_id => $nilai) {
        // Validasi nilai antara 0-100
        $nilai = max(0, min(100, (int)$nilai));

        if ($peran === 'pengawas') {
            $this->Penilaian_model->simpan_nilai_pengawas($all_id, $kriteria_id, $nilai);
        } else {
            $this->Penilaian_model->simpan_nilai($all_id, $kriteria_id, $nilai);
        }
    }

    $this->session->set_flashdata('message', '<div class="alert alert-success">Nilai berhasil disimpan.</div>');
    redirect($this->agent->referrer());
}

    public function pilihkegiatan()
    {
        $data['title'] = 'Cetak Hasil Penilaian';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        if ($data['user']['role_id'] == 5) {

            $mitra = $this->db->get_where('mitra', ['email' => $this->session->userdata('email')])->row_array();

            $id_mitra = $mitra['id_mitra'];

            $data['id_mitra'] = $id_mitra;

            $sql = "SELECT kegiatan.*, all_kegiatan_pencacah.id_pengawas FROM kegiatan JOIN all_kegiatan_pencacah ON kegiatan.id = all_kegiatan_pencacah.kegiatan_id WHERE all_kegiatan_pencacah.id_mitra = $id_mitra ORDER BY kegiatan.finish DESC";
            $data['kegiatan'] = $this->db->query($sql)->result_array();

            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('penilaian/cetak-mitra', $data);
            $this->load->view('template/footer');
        } else if ($data['user']['role_id'] == 4) {


            $email = $data['user']['email'];

            $sql_id_peg = "SELECT pegawai.id_peg FROM pegawai JOIN user WHERE pegawai.email LIKE '$email' UNION (SELECT mitra.id_mitra as id_peg FROM mitra JOIN user WHERE mitra.email LIKE '$email')";
            $id_peg = implode($this->db->query($sql_id_peg)->row_array());

            $data['id_peg'] = $id_peg;

            $sql = "SELECT kegiatan.* FROM kegiatan JOIN all_kegiatan_pengawas ON kegiatan.id = all_kegiatan_pengawas.kegiatan_id WHERE all_kegiatan_pengawas.id_pengawas = $id_peg ORDER BY kegiatan.finish DESC";

            $data['kegiatan'] = $this->db->query($sql)->result_array();

            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('penilaian/cetak-pilih-kegiatan', $data);
            $this->load->view('template/footer');
        } else {
            $email = $data['user']['email'];

            $data['kegiatan'] = $this->db->query('SELECT * FROM kegiatan ORDER BY kegiatan.finish DESC')->result_array();

            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('penilaian/cetak-pilih-kegiatan-seksi', $data);
            $this->load->view('template/footer');
        }
    }

    public function pilihmitra_seksi($kegiatan_id)
    {
        $data['title'] = 'Cetak Hasil Penilaian';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $sql = "SELECT mitra.* FROM all_kegiatan_pencacah JOIN mitra ON all_kegiatan_pencacah.id_mitra = mitra.id_mitra WHERE all_kegiatan_pencacah.kegiatan_id = $kegiatan_id";

        $data['mitra'] = $this->db->query($sql)->result_array();

        $data['kegiatan'] = $this->db->get_where('kegiatan', ['id' => $kegiatan_id])->row_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('penilaian/cetak-pilih-mitra-seksi', $data);
        $this->load->view('template/footer');
    }


    public function pilihmitra($kegiatan_id, $id_peg)
    {
        $data['title'] = 'Cetak Hasil Penilaian';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $sql = "SELECT mitra.* FROM all_kegiatan_pencacah JOIN mitra ON all_kegiatan_pencacah.id_mitra = mitra.id_mitra WHERE all_kegiatan_pencacah.kegiatan_id = $kegiatan_id AND all_kegiatan_pencacah.id_pengawas = $id_peg";

        $data['id_peg'] = $id_peg;
        $data['mitra'] = $this->db->query($sql)->result_array();

        $data['kegiatan'] = $this->db->get_where('kegiatan', ['id' => $kegiatan_id])->row_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('penilaian/cetak-pilih-mitra', $data);
        $this->load->view('template/footer');
    }

    public function download($kegiatan_id, $id_peg, $id_mitra)
{
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

    // Ambil id dari all_kegiatan_pencacah
    $sql_all_kegiatan_pencacah_id = "SELECT id FROM all_kegiatan_pencacah WHERE kegiatan_id = $kegiatan_id AND id_mitra = $id_mitra";
    $all_kegiatan_pencacah_id = implode($this->db->query($sql_all_kegiatan_pencacah_id)->row_array());

    // Ambil data penilaian
    $sqlpenilaian = "SELECT all_penilaian.*, kriteria.nama, kriteria.id as kriteria_id 
                    FROM all_penilaian 
                    LEFT JOIN kriteria ON all_penilaian.kriteria_id = kriteria.id 
                    WHERE all_penilaian.all_kegiatan_pencacah_id = $all_kegiatan_pencacah_id 
                    ORDER BY kriteria.id ASC";
    $data['penilaian'] = $this->db->query($sqlpenilaian)->result_array();

    // Ambil data kegiatan
    $data['kegiatan'] = $this->db->get_where('kegiatan', ['id' => $kegiatan_id])->row_array();

    // Ambil data mitra
    $data['mitra'] = $this->db->get_where('mitra', ['id_mitra' => $id_mitra])->row_array();

    // Ambil seksi_id dari kegiatan
    $seksi_id = $data['kegiatan']['seksi_id'] ?? null;

    // Ambil data PJK dari tabel seksi
    $seksi = $this->db->get_where('seksi', ['id' => $seksi_id])->row_array();
    $data['pjk'] = $seksi['pjk'] ?? 'Tidak ditemukan';

    // Hitung jumlah kriteria
    $jumlah_kriteria = $this->db->get('kriteria')->num_rows();

    // Ambil nama penilai (pegawai atau mitra)
    $sqlpenilai = "SELECT nama, id_peg FROM pegawai WHERE id_peg = $id_peg 
                   UNION 
                   SELECT mitra.nama as nama, mitra.id_mitra as id_peg FROM mitra WHERE id_mitra = $id_peg";
    $data['penilai'] = $this->db->query($sqlpenilai)->row_array();

    // Hitung jumlah penilaian
    $sqlrow = "SELECT count(*) as jumlah FROM all_penilaian WHERE all_kegiatan_pencacah_id = $all_kegiatan_pencacah_id";
    $row = $this->db->query($sqlrow)->row()->jumlah;

    $role_id = $data['user']['role_id'];

    // Ambil semua bobot kriteria
    $kriteria = $this->db->get('kriteria')->result_array();
    $bobot_kriteria = [];
    foreach ($kriteria as $k) {
        $bobot_kriteria[$k['id']] = floatval($k['bobot']);
    }

    // Tambahkan bobot dan nilai_terbobot ke setiap penilaian
    foreach ($data['penilaian'] as &$pen) {
        $id_kriteria = $pen['kriteria_id'];
        $bobot = isset($bobot_kriteria[$id_kriteria]) ? $bobot_kriteria[$id_kriteria] : 0;
        $nilai = isset($pen['nilai']) ? floatval($pen['nilai']) : 0;
        $pen['bobot'] = $bobot;
        $pen['nilai_terbobot'] = $nilai * $bobot;
    }
    unset($pen);

    // Cek apakah penilaian lengkap
    if ($row < $jumlah_kriteria) {
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Penilaian belum lengkap!</div>');
        if ($role_id == 5) {
            redirect('penilaian/pilihkegiatan');
        } else {
            redirect('penilaian/pilihmitra/' . $kegiatan_id . '/' . $id_peg);
        }
    } else {
        $this->load->view('penilaian/laporan', $data);
    }
}


public function download_seksi($kegiatan_id, $id_mitra)
{
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

    $sql_all_kegiatan_pencacah_id = "SELECT id FROM all_kegiatan_pencacah WHERE kegiatan_id = $kegiatan_id AND id_mitra = $id_mitra";
    $all_kegiatan_pencacah_id = implode($this->db->query($sql_all_kegiatan_pencacah_id)->row_array());

    $sqlpenilaian = "SELECT all_penilaian.*, kriteria.nama, kriteria.prioritas, kriteria.id as kriteria_id 
                    FROM all_penilaian 
                    LEFT JOIN kriteria ON all_penilaian.kriteria_id = kriteria.id 
                    WHERE all_penilaian.all_kegiatan_pencacah_id = $all_kegiatan_pencacah_id 
                    ORDER BY kriteria.prioritas ASC";
    $data['penilaian'] = $this->db->query($sqlpenilaian)->result_array();

    // Ambil kegiatan
    $data['kegiatan'] = $this->db->get_where('kegiatan', ['id' => $kegiatan_id])->row_array();

    // Ambil mitra
    $data['mitra'] = $this->db->get_where('mitra', ['id_mitra' => $id_mitra])->row_array();

    // Ambil seksi_id dari kegiatan
    $seksi_id = $data['kegiatan']['seksi_id']; // pastikan nama kolom benar

    // Ambil data seksi
    $seksi = $this->db->get_where('seksi', ['id' => $seksi_id])->row_array();

    // Simpan nama PJK dari kolom 'pjk' di seksi
    $data['pjk'] = $seksi['pjk'] ?? 'Tidak ditemukan';

    // Hitung jumlah kriteria
    $jumlah_kriteria = $this->db->get('kriteria')->num_rows();

    // Ambil ID pegawai penilai
    $query_id_peg = "SELECT id_pengawas FROM all_kegiatan_pencacah WHERE kegiatan_id = $kegiatan_id AND id_mitra = $id_mitra";
    $id_peg = implode($this->db->query($query_id_peg)->row_array());

    // Ambil nama penilai
    $sqlpenilai = "SELECT nama, id_peg FROM pegawai WHERE id_peg = $id_peg 
                   UNION 
                   SELECT mitra.nama as nama, mitra.id_mitra as id_peg FROM mitra WHERE id_mitra = $id_peg";
    $data['penilai'] = $this->db->query($sqlpenilai)->row_array();

    // Cek apakah penilaian sudah lengkap
    $sqlrow = "SELECT count(*) as jumlah FROM all_penilaian WHERE all_kegiatan_pencacah_id = $all_kegiatan_pencacah_id";
    $row = $this->db->query($sqlrow)->row()->jumlah;

    $role_id = $data['user']['role_id'];

    // Ambil bobot kriteria
    $kriteria = $this->db->get('kriteria')->result_array();
    $bobot_kriteria = [];
    foreach ($kriteria as $k) {
        $bobot_kriteria[$k['id']] = floatval($k['bobot']);
    }

    // Hitung nilai terbobot
    foreach ($data['penilaian'] as &$pen) {
        $id_kriteria = $pen['kriteria_id'];
        $bobot = isset($bobot_kriteria[$id_kriteria]) ? $bobot_kriteria[$id_kriteria] : 0;
        $nilai = isset($pen['nilai']) ? floatval($pen['nilai']) : 0;
        $pen['bobot'] = $bobot;
        $pen['nilai_terbobot'] = $nilai * $bobot;
    }
    unset($pen);

    if ($row < $jumlah_kriteria) {
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Penilaian belum lengkap!</div>');
        if ($role_id == 5) {
            redirect('penilaian/pilihkegiatan');
        } else {
            redirect('penilaian/pilihmitra_seksi/' . $kegiatan_id);
        }
    } else {
        usort($data['penilaian'], function($a, $b) {
            return $a['prioritas'] <=> $b['prioritas'];
        });

        $this->load->view('penilaian/laporan', $data);
    }
}

public function cetak_ranking($kegiatan_id)
{
    $data['title'] = 'Cetak Ranking Mitra';

    // Ambil data user login
    $data['user'] = $this->db->get_where('user', [
        'email' => $this->session->userdata('email')
    ])->row_array();

    // Ambil kegiatan
    $data['kegiatan'] = $this->db->get_where('kegiatan', [
        'id' => $kegiatan_id
    ])->row_array();

    // Ambil data seksi & PJK
    $seksi_id = $data['kegiatan']['seksi_id'] ?? null;
    $seksi = $this->db->get_where('seksi', ['id' => $seksi_id])->row_array();
    $data['pjk'] = $seksi['pjk'] ?? 'Tidak ditemukan';
    $data['nip_pjk'] = $seksi['nip_pjk'] ?? '-';

    // Ambil semua all_kegiatan_pencacah terkait kegiatan ini
    $list_kegiatan = $this->db->get_where('all_kegiatan_pencacah', [
        'kegiatan_id' => $kegiatan_id
    ])->result_array();

    // Ambil semua kriteria dan bobot
    $kriteria = $this->db->get('kriteria')->result_array();
    $bobot_kriteria = [];
    foreach ($kriteria as $k) {
        $bobot_kriteria[$k['id']] = floatval($k['bobot']);
    }
    $jumlah_kriteria = count($kriteria);

    // Siapkan array mitra ranking
    $ranking = [];

    foreach ($list_kegiatan as $row) {
        $id_akp = $row['id'];
        $id_mitra = $row['id_mitra'];

        // Ambil data mitra
        $mitra = $this->db->get_where('mitra', ['id_mitra' => $id_mitra])->row_array();

        // Ambil penilaian untuk mitra ini
        $sqlpenilaian = "SELECT * FROM all_penilaian WHERE all_kegiatan_pencacah_id = $id_akp";
        $penilaian = $this->db->query($sqlpenilaian)->result_array();

        if (count($penilaian) < $jumlah_kriteria) {
            continue; // lewati jika penilaian belum lengkap
        }

        // Hitung nilai terbobot total
        $total = 0;
        foreach ($penilaian as $pen) {
            $nilai = floatval($pen['nilai']);
            $bobot = $bobot_kriteria[$pen['kriteria_id']] ?? 0;
            $total += $nilai * $bobot;
        }

        $ranking[] = [
            'nama_mitra' => $mitra['nama'],
            'id_mitra' => $mitra['id_mitra'],
            'nilai_akhir' => $total,
        ];
    }

    // Urutkan ranking berdasarkan nilai_akhir
    usort($ranking, function($a, $b) {
        return $b['nilai_akhir'] <=> $a['nilai_akhir'];
    });

    // Tambahkan nomor ranking
    $i = 1;
    foreach ($ranking as &$r) {
        $r['ranking'] = $i++;
    }

    // Kirim data ke view
    $data['ranking'] = $ranking;

    // Tampilkan ke view cetak
    $this->load->view('penilaian/cetak_ranking', $data);
}



    public function arsip()
    {
        $data['title'] = 'Arsip';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $sql = "SELECT * FROM mitra WHERE is_active = 1";
        $data['mitra'] = $this->db->query($sql)->result_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('penilaian/arsip', $data);
        $this->load->view('template/footer');
    }

        public function arsip_pilihkegiatan($id_mitra)
    {
        $data['title'] = 'Arsip';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // Ambil semua kegiatan yang dimiliki mitra
        $sql = "SELECT kegiatan.* 
                FROM kegiatan 
                JOIN all_kegiatan_pencacah ON kegiatan.id = all_kegiatan_pencacah.kegiatan_id 
                WHERE all_kegiatan_pencacah.id_mitra = $id_mitra";
        $data['kegiatan'] = $this->db->query($sql)->result_array();

        // Ambil semua penilaian berdasarkan mitra
        $sql_nilai = "SELECT kriteria.nama, all_penilaian.nilai
                    FROM all_penilaian
                    JOIN kriteria ON all_penilaian.kriteria_id = kriteria.id
                    JOIN all_kegiatan_pencacah ON all_penilaian.all_kegiatan_pencacah_id = all_kegiatan_pencacah.id
                    WHERE all_kegiatan_pencacah.id_mitra = $id_mitra";
        $data['nilai'] = $this->db->query($sql_nilai)->result_array();

        $data['id_mitra'] = $id_mitra;

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('penilaian/arsip-pilihkegiatan', $data);
        $this->load->view('template/footer');
    }


}
