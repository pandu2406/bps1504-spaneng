<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kegiatan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in_user();
        $this->load->model('Kegiatan_model');
    }

    public function index()
    {
        $data['title'] = 'Kegiatan';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // Hitung jumlah Survei
        $this->db->like('nama', 'Survei');
        $survei = $this->db->get('kegiatan')->num_rows();

        // Hitung jumlah Sensus
        $this->db->like('nama', 'Sensus');
        $sensus = $this->db->get('kegiatan')->num_rows();

        $data['jumlah_survei'] = $survei;
        $data['jumlah_sensus'] = $sensus;

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('kegiatan/index', $data);
        $this->load->view('template/footer');
    }

    public function survei()
{
    $data['title'] = 'Survei';
    $data['user'] = $this->db->get_where('user', [
        'email' => $this->session->userdata('email')
    ])->row_array();

    // Data untuk dropdown
    $data['survei'] = $this->db->query("
        SELECT kegiatan.*, seksi.nama AS nama_seksi 
        FROM kegiatan 
        LEFT JOIN seksi ON kegiatan.seksi_id = seksi.id 
        WHERE kegiatan.jenis_kegiatan = 1 
        ORDER BY kegiatan.finish DESC
    ")->result_array();

    $data['seksi'] = $this->db->get('seksi')->result_array();
    $data['posisi'] = $this->db->get('posisi')->result_array();
    $data['satuan'] = $this->db->get('satuan')->result_array();
    $data['sistempembayaran_list'] = $this->db->get('sistempembayaran')->result_array();

    // Validasi Form Input
    $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
    $this->form_validation->set_rules('start', 'Start', 'required|trim');
    $this->form_validation->set_rules('finish', 'Finish', 'required|trim');
    $this->form_validation->set_rules('k_pengawas', 'Kuota Pengawas', 'required|trim|integer');
    $this->form_validation->set_rules('k_pencacah', 'Kuota Pencacah', 'required|trim|integer');
    $this->form_validation->set_rules('seksi_id', 'Penanggung Jawab', 'required|trim|integer');
    $this->form_validation->set_rules('posisi', 'Jenis Kegiatan', 'required|trim|integer');
    $this->form_validation->set_rules('satuan', 'Satuan', 'required|trim|integer');
    $this->form_validation->set_rules('honor', 'Honor', 'required|trim|numeric');
    $this->form_validation->set_rules('ob', 'Pulsa', 'required|trim');

    // Jika validasi gagal
    if ($this->form_validation->run() == false) {
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('kegiatan/survei', $data);
        $this->load->view('template/footer');
    } else {
        $start = strtotime($this->input->post('start'));
        $finish = strtotime($this->input->post('finish'));

        // Validasi logika tanggal
        if ($finish >= $start) {
            $insert = [
                'nama' => $this->input->post('nama', true),
                'start' => $start,
                'finish' => $finish,
                'k_pengawas' => $this->input->post('k_pengawas', true),
                'k_pencacah' => $this->input->post('k_pencacah', true),
                'jenis_kegiatan' => 1, // 1 untuk survei
                'seksi_id' => $this->input->post('seksi_id', true),
                'posisi' => $this->input->post('posisi', true),
                'satuan' => $this->input->post('satuan', true),
                'honor' => $this->input->post('honor', true),
                'ob' => $this->input->post('ob', true),
            ];

            $this->db->insert('kegiatan', $insert);
            $this->session->set_flashdata('message', '<div class="alert alert-success">Survei berhasil ditambahkan.</div>');
            redirect('kegiatan/survei');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Tanggal selesai tidak boleh lebih awal dari tanggal mulai!</div>');
            redirect('kegiatan/survei');
        }
    }
}

public function sensus()
{
    $data['title'] = 'Sensus';
    $data['user'] = $this->db->get_where('user', [
        'email' => $this->session->userdata('email')
    ])->row_array();

    // Data kegiatan sensus dan dropdown
    $data['sensus'] = $this->db->query("
        SELECT kegiatan.*, seksi.nama AS nama_seksi 
        FROM kegiatan 
        LEFT JOIN seksi ON kegiatan.seksi_id = seksi.id 
        WHERE kegiatan.jenis_kegiatan = 2 
        ORDER BY kegiatan.finish DESC
    ")->result_array();

    $data['seksi'] = $this->db->get('seksi')->result_array();
    $data['posisi'] = $this->db->get('posisi')->result_array();
    $data['satuan'] = $this->db->get('satuan')->result_array();
    $data['sistempembayaran_list'] = $this->db->get('sistempembayaran')->result_array();

    // Validasi form
    $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
    $this->form_validation->set_rules('start', 'Start', 'required|trim');
    $this->form_validation->set_rules('finish', 'Finish', 'required|trim');
    $this->form_validation->set_rules('k_pengawas', 'Kuota Pengawas', 'required|trim|integer');
    $this->form_validation->set_rules('k_pencacah', 'Kuota Pencacah', 'required|trim|integer');
    $this->form_validation->set_rules('seksi_id', 'Penanggung Jawab', 'required|trim|integer');
    $this->form_validation->set_rules('posisi', 'Jenis Kegiatan', 'required|trim|integer');
    $this->form_validation->set_rules('satuan', 'Satuan', 'required|trim|integer');
    $this->form_validation->set_rules('honor', 'Honor', 'required|trim|numeric');
    $this->form_validation->set_rules('ob', 'Pulsa', 'required|trim');

    if ($this->form_validation->run() == false) {
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('kegiatan/sensus', $data);
        $this->load->view('template/footer');
    } else {
        $start = strtotime($this->input->post('start'));
        $finish = strtotime($this->input->post('finish'));

        if ($finish >= $start) {
            $insert = [
                'nama' => $this->input->post('nama', true),
                'start' => $start,
                'finish' => $finish,
                'k_pengawas' => $this->input->post('k_pengawas', true),
                'k_pencacah' => $this->input->post('k_pencacah', true),
                'jenis_kegiatan' => 2, // 2 untuk sensus
                'seksi_id' => $this->input->post('seksi_id', true),
                'posisi' => $this->input->post('posisi', true),
                'satuan' => $this->input->post('satuan', true),
                'honor' => $this->input->post('honor', true),
                'ob' => $this->input->post('ob', true),
            ];

            $this->db->insert('kegiatan', $insert);
            $this->session->set_flashdata('message', '<div class="alert alert-success">Sensus berhasil ditambahkan.</div>');
            redirect('kegiatan/sensus');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Tanggal selesai tidak boleh lebih awal dari tanggal mulai!</div>');
            redirect('kegiatan/sensus');
        }
    }
}

    public function editsurvei($id)
{
    $data['title'] = 'Edit Survei';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
    $data['survei'] = $this->db->get_where('kegiatan', ['id' => $id])->row_array();

    // Ambil data dropdown
    $data['daftar_seksi'] = $this->db->get('seksi')->result_array();
    $data['posisi'] = $this->db->get('posisi')->result_array();
    $data['satuan'] = $this->db->get('satuan')->result_array();
    $data['sistempembayaran_list'] = $this->db->get('sistempembayaran')->result_array();

    // Validasi
    $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
    $this->form_validation->set_rules('start', 'Start', 'required|trim');
    $this->form_validation->set_rules('finish', 'Finish', 'required|trim');
    $this->form_validation->set_rules('k_pengawas', 'Kuota Pengawas', 'required|trim|integer');
    $this->form_validation->set_rules('k_pencacah', 'Kuota Pencacah', 'required|trim|integer');
    $this->form_validation->set_rules('seksi_id', 'Penanggung Jawab', 'required|trim|integer');
    $this->form_validation->set_rules('posisi', 'Jenis Kegiatan', 'required|trim|integer');
    $this->form_validation->set_rules('satuan', 'Satuan', 'required|trim|integer');
    $this->form_validation->set_rules('honor', 'Honor', 'required|trim|numeric');
    $this->form_validation->set_rules('ob', 'Pulsa', 'required|trim');

    if ($this->form_validation->run() == false) {
        // Load view dengan data form sebelumnya
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('kegiatan/edit-survei', $data);
        $this->load->view('template/footer');
    } else {
        $start = strtotime($this->input->post('start'));
        $finish = strtotime($this->input->post('finish'));

        if ($finish >= $start) {
            $update = [
                'nama' => $this->input->post('nama', true),
                'start' => $start,
                'finish' => $finish,
                'k_pengawas' => $this->input->post('k_pengawas', true),
                'k_pencacah' => $this->input->post('k_pencacah', true),
                'seksi_id' => $this->input->post('seksi_id', true),
                'posisi' => $this->input->post('posisi', true),
                'satuan' => $this->input->post('satuan', true),
                'honor' => $this->input->post('honor', true),
                'ob' => $this->input->post('ob', true)
            ];

            $this->db->where('id', $id);
            $this->db->update('kegiatan', $update);

            $this->session->set_flashdata('message', '<div class="alert alert-success">Survei berhasil diperbarui.</div>');
            redirect('kegiatan/survei');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Tanggal selesai tidak boleh lebih awal dari tanggal mulai!</div>');
            redirect('kegiatan/editsurvei/' . $id);
        }
    }
}

public function editsensus($id)
{
    $data['title'] = 'Edit Sensus';
    $data['user'] = $this->db->get_where('user', [
        'email' => $this->session->userdata('email')
    ])->row_array();
    $data['sensus'] = $this->db->get_where('kegiatan', ['id' => $id])->row_array();

    // Ambil data dropdown tambahan (jika digunakan seperti di survei)
    $data['daftar_seksi'] = $this->db->get('seksi')->result_array();
    $data['posisi'] = $this->db->get('posisi')->result_array();
    $data['satuan'] = $this->db->get('satuan')->result_array();
    $data['sistempembayaran_list'] = $this->db->get('sistempembayaran')->result_array();

    // Validasi form
    $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
    $this->form_validation->set_rules('start', 'Start', 'required|trim');
    $this->form_validation->set_rules('finish', 'Finish', 'required|trim');
    $this->form_validation->set_rules('k_pengawas', 'Kuota Pengawas', 'required|trim|integer');
    $this->form_validation->set_rules('k_pencacah', 'Kuota Pencacah', 'required|trim|integer');
    $this->form_validation->set_rules('seksi_id', 'Penanggung Jawab', 'required|trim|integer');
    $this->form_validation->set_rules('posisi', 'Jenis Kegiatan', 'required|trim|integer');
    $this->form_validation->set_rules('satuan', 'Satuan', 'required|trim|integer');
    $this->form_validation->set_rules('honor', 'Honor', 'required|trim|numeric');
    $this->form_validation->set_rules('ob', 'Pulsa', 'required|trim');

    if ($this->form_validation->run() == false) {
        // Tampilkan kembali form dengan data lama
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('kegiatan/edit-sensus', $data);
        $this->load->view('template/footer');
    } else {
        $start = strtotime($this->input->post('start'));
        $finish = strtotime($this->input->post('finish'));

        if ($finish >= $start) {
            $update = [
                'nama' => $this->input->post('nama', true),
                'start' => $start,
                'finish' => $finish,
                'k_pengawas' => $this->input->post('k_pengawas', true),
                'k_pencacah' => $this->input->post('k_pencacah', true),
                'seksi_id' => $this->input->post('seksi_id', true),
                'posisi' => $this->input->post('posisi', true),
                'satuan' => $this->input->post('satuan', true),
                'honor' => $this->input->post('honor', true),
                'ob' => $this->input->post('ob', true)
            ];

            $this->db->where('id', $id);
            $this->db->update('kegiatan', $update);

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Sensus berhasil diperbarui.</div>');
            redirect('kegiatan/sensus');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Tanggal selesai tidak boleh lebih awal dari tanggal mulai!</div>');
            redirect('kegiatan/editsensus/' . $id);
        }
    }
}


    function deletesurvei($id)
    {
        $q1 = "SELECT id FROM all_kegiatan_pencacah WHERE kegiatan_id = $id";

        $q2 = "DELETE FROM all_penilaian WHERE all_kegiatan_pencacah_id IN ($q1)";
        $q22 = $this->db->query($q2);

        $this->Kegiatan_model->deletesurvei_all_kegiatan_pencacah($id);
        $this->Kegiatan_model->deletesurvei_all_kegiatan_pengawas($id);

        $this->Kegiatan_model->deletesurvei($id);
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Survei has been deleted!</div>');
        redirect('kegiatan/survei');
    }

    function deletesensus($id)
    {
        $q1 = "SELECT id FROM all_kegiatan_pencacah WHERE kegiatan_id = $id";

        $q2 = "DELETE FROM all_penilaian WHERE all_kegiatan_pencacah_id IN ($q1)";
        $q22 = $this->db->query($q2);

        $this->Kegiatan_model->deletesensus_all_kegiatan_pencacah($id);
        $this->Kegiatan_model->deletesensus_all_kegiatan_pengawas($id);
        $this->Kegiatan_model->deletesensus($id);
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Sensus has been deleted!</div>');
        redirect('kegiatan/sensus');
    }

    public function tambah_pencacah($id)
{
    $data['title'] = 'Tambah Pencacah';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

    $sql_waktu = "SELECT kegiatan.start, kegiatan.finish FROM kegiatan WHERE kegiatan.id = $id";
    $waktu = $this->db->query($sql_waktu)->row();

    $sql_bentuk_kegiatan = "SELECT kegiatan.ob FROM kegiatan WHERE kegiatan.id = $id";
    $bentuk_kegiatan = (int) implode($this->db->query($sql_bentuk_kegiatan)->row_array());

    // Ambil semua mitra aktif, kecuali yang jadi pengawas di kegiatan ini
    $sql_id_mitra_pengawas = "SELECT id_pengawas FROM all_kegiatan_pengawas WHERE kegiatan_id = $id";
    $sql_pencacah = "SELECT mitra.* FROM mitra 
    WHERE mitra.is_active = 1 
    AND mitra.id_mitra NOT IN ($sql_id_mitra_pengawas)";


    $data['pencacah'] = $this->db->query($sql_pencacah)->result_array();
    $data['kuota'] = $this->db->query("SELECT COUNT(kegiatan_id) AS kegiatan_id FROM all_kegiatan_pencacah WHERE kegiatan_id = $id")->row_array();
    $data['kegiatan'] = $this->db->get_where('kegiatan', ['id' => $id])->row_array();

    // --------- HANDLE SUBMIT FORM -----------
    if ($this->input->post()) {
        $id_mitra = $this->input->post('id_mitra'); // pastikan form punya input name="id_mitra"

        // Ambil id_pengawas dari all_kegiatan_pengawas
        $pengawas = $this->db->get_where('all_kegiatan_pengawas', ['kegiatan_id' => $id])->row();
        $id_pengawas = $pengawas ? $pengawas->id_pengawas : 0; // fallback 0 jika tidak ada

        // Simpan ke all_kegiatan_pencacah
        $insert = [
            'kegiatan_id' => $id,
            'id_mitra' => $id_mitra,
            'id_pengawas' => $id_pengawas
        ];
        $this->db->insert('all_kegiatan_pencacah', $insert);

        $this->session->set_flashdata('message', '<div class="alert alert-success">Pencacah berhasil ditambahkan!</div>');
        redirect('kegiatan/tambah_pencacah/' . $id);
    }

    // --------- TAMPILKAN FORM ---------
    $this->load->view('template/header', $data);
    $this->load->view('template/sidebar', $data);
    $this->load->view('template/topbar', $data);
    $this->load->view('kegiatan/tambah-pencacah', $data);
    $this->load->view('template/footer');
}


    function mitraterpilih($id)
    {
        $data['title'] = 'Pencacah Terpilih';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $sqlpencacah = "SELECT mitra.* FROM all_kegiatan_pencacah JOIN mitra ON all_kegiatan_pencacah.id_mitra = mitra.id_mitra WHERE all_kegiatan_pencacah.kegiatan_id = $id";
        $data['pencacah'] = $this->db->query($sqlpencacah)->result_array();

        $sqlkuota = "SELECT count(kegiatan_id) as kegiatan_id FROM all_kegiatan_pencacah WHERE kegiatan_id = $id";
        $data['kuota'] = $this->db->query($sqlkuota)->row_array();

        $data['kegiatan'] = $this->db->get_where('kegiatan', ['id' => $id])->row_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('kegiatan/mitra-terpilih', $data);
        $this->load->view('template/footer');
    }

    function details_kegiatan_mitra($kegiatan_id, $id_mitra)
    {
        $data['title'] = 'Details Kegiatan';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $now = time();

        $sql = "SELECT all_kegiatan_pencacah.*, kegiatan.* FROM all_kegiatan_pencacah INNER JOIN kegiatan ON all_kegiatan_pencacah.kegiatan_id = kegiatan.id WHERE all_kegiatan_pencacah.id_mitra = $id_mitra AND ((kegiatan.start <= $now AND kegiatan.finish >= $now) OR (kegiatan.start > $now)) ORDER BY kegiatan.start";

        $data['details'] = $this->db->query($sql)->result_array();
        $jumlahkegiatan = count($data['details']);

        if ($jumlahkegiatan > 0) {

            $data['id_mitra'] = $this->db->get_where('mitra', ['id_mitra' => $id_mitra])->row_array();

            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('kegiatan/details-kegiatan-mitra', $data);
            $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Mitra belum mengikuti kegiatan</div>');
            redirect('kegiatan/tambah_pencacah/' . $kegiatan_id);
        }
    }

    function details_mitra_kegiatan($id_mitra)
    {
        $data['title'] = 'Details Kegiatan';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $sql = "SELECT kegiatan.* FROM all_kegiatan_pencacah INNER JOIN kegiatan ON all_kegiatan_pencacah.kegiatan_id = kegiatan.id WHERE all_kegiatan_pencacah.id_mitra = $id_mitra";

        $data['id_mitra'] = $this->db->get_where('mitra', ['id_mitra' => $id_mitra])->row_array();

        $data['details'] = $this->db->query($sql)->result_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('kegiatan/details-mitra-kegiatan', $data);
        $this->load->view('template/footer');
    }

    function details_nilai_perkegiatan($id_mitra, $kegiatan_id)
    {
        $data['title'] = 'Details Nilai Per Kegiatan';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['id_mitra'] = $this->db->get_where('mitra', ['id_mitra' => $id_mitra])->row_array();
        $data['kegiatan'] = $this->db->get_where('kegiatan', ['id' => $kegiatan_id])->row_array();
        $data['kriteria'] = $this->db->get('kriteria')->result_array();

        $sqlnilai = "SELECT all_penilaian.*, kriteria.nama FROM all_penilaian LEFT JOIN kriteria ON all_penilaian.kriteria_id = kriteria.id  JOIN all_kegiatan_pencacah ON all_penilaian.all_kegiatan_pencacah_id = all_kegiatan_pencacah.id WHERE all_kegiatan_pencacah.kegiatan_id = $kegiatan_id AND all_kegiatan_pencacah.id_mitra = $id_mitra";
        $data['nilai'] = $this->db->query($sqlnilai)->result_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('kegiatan/details-nilai-perkegiatan', $data);
        $this->load->view('template/footer');
    }

    public function changepencacah()
{
    $kegiatan_id = $this->input->post('kegiatanId');
    $id_mitra = $this->input->post('mitraId');

    // Ambil kuota pencacah dari tabel kegiatan
    $kuota = $this->db->get_where('kegiatan', ['id' => $kegiatan_id])->row_array();
    $intkuota = (int) $kuota['k_pencacah'];

    // Hitung pencacah yang sudah ditugaskan
    $cek_kuota = $this->db->get_where('all_kegiatan_pencacah', ['kegiatan_id' => $kegiatan_id])->num_rows();

    $data = [
        'kegiatan_id' => $kegiatan_id,
        'id_mitra' => $id_mitra
    ];

    // Ambil email mitra
    $queryemail = "SELECT email FROM mitra WHERE id_mitra = $id_mitra";
    $email = implode($this->db->query($queryemail)->row_array());

    $data2 = [
        'email' => $email,
        'role_id' => '5',
        'date_created' => time()
    ];

    $result = $this->db->get_where('all_kegiatan_pencacah', $data);

    // Jika belum pernah ditambahkan, maka tambahkan
    if ($result->num_rows() < 1) {
        if ($cek_kuota < $intkuota) {
            $this->db->insert('all_kegiatan_pencacah', $data);

            // Cek apakah email mitra sudah ada di tabel user
            $check = $this->Kegiatan_model->check_email($email);
            if ($check < 1) {
                $this->db->insert('user', $data2);
            }

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Pencacah berhasil ditambahkan!</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Kuota pencacah penuh!</div>');
        }
    }
    // Jika sudah ada, maka hapus semua data terkait
    else {
        // Ambil ID dari all_kegiatan_pencacah
        $all_kegiatan_pencacah_id = $this->db->get_where('all_kegiatan_pencacah', [
            'kegiatan_id' => $kegiatan_id,
            'id_mitra' => $id_mitra
        ])->row_array();

        $data3 = [
            'all_kegiatan_pencacah_id' => $all_kegiatan_pencacah_id['id']
        ];

        // Hapus data penilaian
        $this->db->delete('all_penilaian', $data3);

        // Hapus data rincian honor dari rinciankegiatan
        $this->db->where('id_mitra', $id_mitra);
        $this->db->where('kegiatan_id', $kegiatan_id);
        $this->db->delete('rinciankegiatan');

        // Hapus data dari all_kegiatan_pencacah
        $this->db->delete('all_kegiatan_pencacah', $data);

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Pencacah berhasil dihapus!</div>');
    }
}

    function tambah_pengawas($id)
    {
        $data['title'] = 'Tambah Pengawas';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $sqlpengawas = "SELECT pegawai.* FROM pegawai";
        $data['pengawas'] = $this->db->query($sqlpengawas)->result_array();

        $sqlkuota = "SELECT count(kegiatan_id) as kegiatan_id FROM all_kegiatan_pengawas WHERE kegiatan_id = $id";
        $data['kuota'] = $this->db->query($sqlkuota)->row_array();

        $data['kegiatan'] = $this->db->get_where('kegiatan', ['id' => $id])->row_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('kegiatan/tambah-pengawas', $data);
        $this->load->view('template/footer');
    }

    function tambah_pengawas_mitra($id)
    {
        $data['title'] = 'Tambah Pengawas';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $sql_pengawas_mitra = "SELECT id_mitra FROM all_kegiatan_pencacah WHERE kegiatan_id = $id";

        $sqlpengawas = "SELECT mitra.* FROM mitra WHERE id_mitra NOT IN ($sql_pengawas_mitra)";
        $data['pengawas'] = $this->db->query($sqlpengawas)->result_array();

        $sqlkuota = "SELECT count(kegiatan_id) as kegiatan_id FROM all_kegiatan_pengawas WHERE kegiatan_id = $id";
        $data['kuota'] = $this->db->query($sqlkuota)->row_array();

        $data['kegiatan'] = $this->db->get_where('kegiatan', ['id' => $id])->row_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('kegiatan/tambah-pengawas-dari-mitra', $data);
        $this->load->view('template/footer');
    }

    public function changepengawas()
    {
        $kegiatan_id = $this->input->post('kegiatanId');
        $id_peg = $this->input->post('id_peg');

        $kuota = $this->db->get_where('kegiatan', ['id' => $kegiatan_id])->row_array();
        $intkuota = (int) $kuota['k_pengawas'];


        $cek_kuota = $this->db->get_where('all_kegiatan_pengawas', ['kegiatan_id' => $kegiatan_id])->num_rows();

        $data = [
            'kegiatan_id' => $kegiatan_id,
            'id_pengawas' => $id_peg
        ];

        $result = $this->db->get_where('all_kegiatan_pengawas', $data);

        if ($result->num_rows() < 1) {
            if ($cek_kuota < $intkuota) {
                $this->db->insert('all_kegiatan_pengawas', $data);
                redirect('kegiatan/tambah_pengawas_ke_user/' . $id_peg);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Kuota penuh!</div>');
            }
        } else {
            $query = "UPDATE all_kegiatan_pencacah SET id_pengawas = 0 WHERE kegiatan_id = $kegiatan_id AND id_pengawas = $id_peg";
            $this->db->query($query);

            $this->db->delete('all_kegiatan_pengawas', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Pengawas changed!</div>');
        }
    }

    public function changepengawas_mitra()
{
    $kegiatan_id = $this->input->post('kegiatanId');
    $id_mitra = $this->input->post('id_mitra'); // pengawas disimpan dengan id_mitra juga

    // Ambil kuota pengawas dari kegiatan
    $kuota = $this->db->get_where('kegiatan', ['id' => $kegiatan_id])->row_array();
    $intkuota = (int) $kuota['k_pengawas'];

    // Hitung jumlah pengawas yang sudah ditugaskan
    $cek_kuota = $this->db->get_where('all_kegiatan_pengawas', ['kegiatan_id' => $kegiatan_id])->num_rows();

    $data = [
        'kegiatan_id' => $kegiatan_id,
        'id_pengawas' => $id_mitra
    ];

    $result = $this->db->get_where('all_kegiatan_pengawas', $data);

    // Jika pengawas belum diassign, tambahkan
    if ($result->num_rows() < 1) {
        if ($cek_kuota < $intkuota) {
            $this->db->insert('all_kegiatan_pengawas', $data);
            redirect('kegiatan/tambah_pengawas_mitra_ke_user/' . $id_mitra);
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Kuota pengawas penuh!</div>');
        }
    }
    // Jika sudah diassign, maka batalkan dan hapus semua yang terkait
    else {
        // Kosongkan referensi pengawas di all_kegiatan_pencacah
        $this->db->query("UPDATE all_kegiatan_pencacah SET id_pengawas = 0 WHERE kegiatan_id = $kegiatan_id AND id_pengawas = $id_mitra");

        // Hapus data penilaian pengawas
        $this->db->delete('all_penilaian_pengawas', [
            'kegiatan_id' => $kegiatan_id,
            'id_pengawas' => $id_mitra
        ]);

        // Hapus data rincian honor dari rinciankegiatan (pengawas = mitra yang berperan sebagai pengawas)
        $this->db->where('id_mitra', $id_mitra);
        $this->db->where('kegiatan_id', $kegiatan_id);
        $this->db->delete('rinciankegiatan');

        // Hapus dari all_kegiatan_pengawas
        $this->db->delete('all_kegiatan_pengawas', $data);

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Pengawas berhasil dihapus!</div>');
    }
}

    function pengawasterpilih($kegiatan_id)
{
    $data['title'] = 'Pengawas Terpilih';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

    // Query untuk pengawas dari pegawai
    $sqlpengawas1 = 
    "SELECT pegawai.id_peg as id, pegawai.email as email, pegawai.nama as nama, 'pegawai' as asal 
    FROM all_kegiatan_pengawas 
    JOIN pegawai ON all_kegiatan_pengawas.id_pengawas = pegawai.id_peg 
    WHERE all_kegiatan_pengawas.kegiatan_id = $kegiatan_id";

    // Query untuk pengawas dari mitra
    $sqlpengawas2 = 
    "SELECT mitra.id_mitra as id, mitra.email as email, mitra.nama as nama, 'mitra' as asal 
    FROM all_kegiatan_pengawas 
    JOIN mitra ON all_kegiatan_pengawas.id_pengawas = mitra.id_mitra 
    WHERE all_kegiatan_pengawas.kegiatan_id = $kegiatan_id";

    // Gabungkan hasil query
    $data['pengawas'] = array_merge(
        $this->db->query($sqlpengawas1)->result_array(),
        $this->db->query($sqlpengawas2)->result_array()
    );

    // Hitung kuota
    $sqlkuota = "SELECT count(kegiatan_id) as kegiatan_id FROM all_kegiatan_pengawas WHERE kegiatan_id = $kegiatan_id";
    $data['kuota'] = $this->db->query($sqlkuota)->row_array();

    // Ambil data kegiatan
    $data['kegiatan'] = $this->db->get_where('kegiatan', ['id' => $kegiatan_id])->row_array();

    // Load semua view
    $this->load->view('template/header', $data);
    $this->load->view('template/sidebar', $data);
    $this->load->view('template/topbar', $data);
    $this->load->view('kegiatan/pengawas-terpilih', $data);
    $this->load->view('template/footer');
}



    function tambah_pengawas_ke_user($id_peg)
    {
        $sqlnamapegawai = "SELECT email FROM pegawai WHERE id_peg = $id_peg";
        $emailpegawai = implode($this->db->query($sqlnamapegawai)->row_array());


        $sqlcekpegawai = "SELECT * FROM user WHERE email = '$emailpegawai' AND role_id = 4";
        $cekpegawai = $this->db->query($sqlcekpegawai);

        $pegawai = $this->db->get_where('pegawai', ['id_peg' => $id_peg])->row_array();

        $data2 = [

            'email' => $pegawai['email'],
            'role_id' => '4',
            'date_created' => time()
        ];

        if ($cekpegawai->num_rows() < 1) {
            $this->db->insert('user', $data2);
        }
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Pengawas changed!</div>');
    }

    function tambah_pengawas_mitra_ke_user($id_mitra)
    {
        $sqlnamapegawai = "SELECT email FROM mitra WHERE id_mitra = $id_mitra";
        $emailpegawai = implode($this->db->query($sqlnamapegawai)->row_array());


        $sqlcekpegawai = "SELECT * FROM user WHERE email = '$emailpegawai' AND role_id = 4";
        $cekpegawai = $this->db->query($sqlcekpegawai);

        $mitra = $this->db->get_where('mitra', ['id_mitra' => $id_mitra])->row_array();

        $data2 = [

            'email' => $mitra['email'],
            'role_id' => '4',
            'date_created' => time()
        ];

        if ($cekpegawai->num_rows() < 1) {
            $this->db->insert('user', $data2);
        }
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Pengawas changed!</div>');
    }


    function details_kegiatan_pengawas($kegiatan_id, $id)
    {
        $data['title'] = 'Details Kegiatan';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $now = time();

        $sql = "SELECT all_kegiatan_pengawas.*, kegiatan.* FROM all_kegiatan_pengawas INNER JOIN kegiatan ON all_kegiatan_pengawas.kegiatan_id = kegiatan.id WHERE all_kegiatan_pengawas.id_pengawas = $id AND ((kegiatan.start <= $now AND kegiatan.finish >= $now) OR (kegiatan.start > $now)) ORDER BY kegiatan.start";
        $data['details'] = $this->db->query($sql)->result_array();

        $jumlahkegiatan = count($data['details']);

        if ($jumlahkegiatan > 0) {

            $data['pengawas'] = $this->db->get_where('pegawai', ['id_peg' => $id])->row_array();

            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('kegiatan/details-kegiatan-pengawas', $data);
            $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Pengawas belum mengikuti kegiatan</div>');
            redirect('kegiatan/tambah_pengawas/' . $kegiatan_id);
        }
    }

    function details_kegiatan_pengawas_mitra($kegiatan_id, $id)
    {
        $data['title'] = 'Details Kegiatan';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $now = time();

        $sql = "SELECT all_kegiatan_pengawas.*, kegiatan.* FROM all_kegiatan_pengawas INNER JOIN kegiatan ON all_kegiatan_pengawas.kegiatan_id = kegiatan.id WHERE all_kegiatan_pengawas.id_pengawas = $id AND ((kegiatan.start <= $now AND kegiatan.finish >= $now) OR (kegiatan.start > $now)) ORDER BY kegiatan.start";
        $data['details'] = $this->db->query($sql)->result_array();

        $jumlahkegiatan = count($data['details']);

        if ($jumlahkegiatan > 0) {

            $data['pengawas'] = $this->db->get_where('mitra', ['id_mitra' => $id])->row_array();

            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('kegiatan/details-kegiatan-pengawas', $data);
            $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Pengawas belum mengikuti kegiatan</div>');
            redirect('kegiatan/tambah_pengawas_mitra/' . $kegiatan_id);
        }
    }

    function tambah_pencacah_pengawas($kegiatan_id, $id_peg)
    {
        $data['title'] = 'Tambah Pencacah';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $sqlpengawas = "SELECT pegawai.id_peg as id_peg, pegawai.nama as nama, pegawai.email as email FROM pegawai WHERE id_peg = $id_peg UNION (SELECT mitra.id_mitra as id_peg, mitra.nama as nama, mitra.email as email FROM mitra WHERE id_mitra = $id_peg)";
        $data['pengawas'] = $this->db->query($sqlpengawas)->row_array();

        $sqlpencacah = "SELECT all_kegiatan_pencacah.*, mitra.* FROM all_kegiatan_pencacah JOIN mitra ON all_kegiatan_pencacah.id_mitra = mitra.id_mitra WHERE all_kegiatan_pencacah.kegiatan_id = $kegiatan_id AND all_kegiatan_pencacah.id_pengawas = 0";
        $data['pencacah'] = $this->db->query($sqlpencacah)->result_array();

        $data['kegiatan'] = $this->db->get_where('kegiatan', ['id' => $kegiatan_id])->row_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('kegiatan/tambah-pencacah-pengawas', $data);
        $this->load->view('template/footer');
    }

    public function changepencacahpengawas()
    {
        $kegiatan_id = $this->input->post('kegiatanId');
        $id_peg = $this->input->post('id_peg');
        $id_mitra = $this->input->post('id_mitra');

        $data = [
            'kegiatan_id' => $kegiatan_id,
            'id_pengawas' => $id_peg,
            'id_mitra' => $id_mitra
        ];

        $result = $this->db->get_where('all_kegiatan_pencacah', $data);

        if ($result->num_rows() < 1) {

            $query = "UPDATE all_kegiatan_pencacah SET id_pengawas = $id_peg WHERE kegiatan_id = $kegiatan_id AND id_mitra = $id_mitra";
            $this->db->query($query);
        } else {
            $query = "UPDATE all_kegiatan_pencacah SET id_pengawas = 0 WHERE kegiatan_id = $kegiatan_id AND id_mitra = $id_mitra";
            $this->db->query($query);
        }
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Pencacah changed!</div>');
    }

    function pencacahterpilih($kegiatan_id, $id_peg)
    {
        $data['title'] = 'Pencacah Terpilih';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $sqlpengawas = "SELECT pegawai.id_peg as id_peg, pegawai.nama as nama, pegawai.email as email FROM pegawai WHERE id_peg = $id_peg UNION SELECT mitra.id_mitra as id_peg, mitra.nama as nama, mitra.email as email FROM mitra WHERE id_mitra = $id_peg ";
        $data['pengawas'] = $this->db->query($sqlpengawas)->row_array();

        $sqlpencacah = "SELECT all_kegiatan_pencacah.id_mitra, mitra.nama, mitra.nik FROM all_kegiatan_pencacah JOIN mitra ON all_kegiatan_pencacah.id_mitra = mitra.id_mitra WHERE (all_kegiatan_pencacah.kegiatan_id = $kegiatan_id) AND (all_kegiatan_pencacah.id_pengawas = $id_peg)";
        $data['pencacah'] = $this->db->query($sqlpencacah)->result_array();

        $data['kegiatan'] = $this->db->get_where('kegiatan', ['id' => $kegiatan_id])->row_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('kegiatan/pencacah-terpilih', $data);
        $this->load->view('template/footer');
    }
}