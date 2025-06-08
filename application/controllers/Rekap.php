<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rekap extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Rekap_model');
    }

    public function index()
{
    $data['title'] = 'Rekap Mitra';
    $data['user'] = $this->db->get_where('user', [
        'email' => $this->session->userdata('email')
    ])->row_array();

    // Ambil input dari form filter
    $periode = $this->input->get('periode');
    $where_periode = "";

    // Mapping nama bulan ke angka bulan
    $bulan_map = [
        'januari' => 1,
        'februari' => 2,
        'maret' => 3,
        'april' => 4,
        'mei' => 5,
        'juni' => 6,
        'juli' => 7,
        'agustus' => 8,
        'september' => 9,
        'oktober' => 10,
        'november' => 11,
        'desember' => 12
    ];

    if (!empty($periode)) {
        if ($periode == 'tahunan') {
            // Filter berdasarkan tahun ini
            $tahun = date('Y');
            $where_periode = "AND YEAR(FROM_UNIXTIME(kegiatan.start)) = '$tahun'";
        } elseif (array_key_exists($periode, $bulan_map)) {
            $bulan = $bulan_map[$periode];
            $where_periode = "AND MONTH(FROM_UNIXTIME(kegiatan.start)) = '$bulan'";
        }
    }

    // Ambil data rekap dari join tabel
    $sql = "
        SELECT mitra.*, kegiatan.nama AS nama_kegiatan, kegiatan.start, kegiatan.finish
        FROM all_kegiatan_pencacah
        JOIN mitra ON all_kegiatan_pencacah.id_mitra = mitra.id_mitra
        JOIN kegiatan ON all_kegiatan_pencacah.kegiatan_id = kegiatan.id
        WHERE 1=1 $where_periode
        ORDER BY kegiatan.start DESC
    ";

    $data['rekap'] = $this->db->query($sql)->result_array();

    $this->load->view('template/header', $data);
    $this->load->view('template/sidebar', $data);
    $this->load->view('template/topbar', $data);
    $this->load->view('rekap/bkm', $data);
    $this->load->view('template/footer');
    show_404();
}


    public function bk_pegawai()
    {
        $data['title'] = 'Beban Kerja Pegawai';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // Inisialisasi bulan dan tahun default
        $selected_bulan = 0;
        $selected_tahun = 0;

        $nama_bulan = [
            'Semua Bulan', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $label_periode = $nama_bulan[$selected_bulan] . ' ' . ($selected_tahun == 0 ? 'Semua Tahun' : $selected_tahun);

        // Query manual rekap beban kerja pegawai
        $query_rekap = "SELECT pegawai.nama, pegawai.id_peg, 
                            COUNT(all_kegiatan_pengawas.kegiatan_id) AS jk, 
                            COUNT(all_kegiatan_pencacah.kegiatan_id) AS jk_p 
                        FROM pegawai 
                        LEFT JOIN all_kegiatan_pengawas ON (pegawai.id_peg = all_kegiatan_pengawas.id_pengawas) 
                        LEFT JOIN all_kegiatan_pencacah ON (pegawai.id_peg = all_kegiatan_pencacah.id_mitra) 
                        GROUP BY pegawai.id_peg 
                        ORDER BY pegawai.id_peg ASC";

        $data['rekap'] = $this->db->query($query_rekap)->result_array();

        $data['selected_bulan'] = $selected_bulan;
        $data['selected_tahun'] = $selected_tahun;
        $data['label_periode'] = $label_periode;

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('rekap/bkp', $data); // view pegawai
        $this->load->view('template/footer');
    }


    public function bk_mitra()
    {
        $data['title'] = 'Beban Kerja Mitra';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
    
        $selected_bulan = 0;
    $selected_tahun = 0;

    $nama_bulan = [
        'Semua Bulan', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $label_periode = $nama_bulan[$selected_bulan] . ' ' . ($selected_tahun == 0 ? 'Semua Tahun' : $selected_tahun);

    
        // Jika kamu ingin query manual:
        $query_rekap = "SELECT mitra.nama, mitra.id_mitra, 
                        count(all_kegiatan_pengawas.kegiatan_id) as jk, 
                        count(all_kegiatan_pencacah.kegiatan_id) as jk_p 
                        FROM mitra 
                        LEFT JOIN all_kegiatan_pengawas ON (mitra.id_mitra = all_kegiatan_pengawas.id_pengawas) 
                        LEFT JOIN all_kegiatan_pencacah ON (mitra.id_mitra = all_kegiatan_pencacah.id_mitra) 
                        GROUP BY mitra.id_mitra 
                        ORDER BY mitra.id_mitra ASC";
    
        $data['rekap'] = $this->db->query($query_rekap)->result_array();
    
        $data['selected_bulan'] = $selected_bulan;
        $data['selected_tahun'] = $selected_tahun;
        $data['label_periode'] = $label_periode;

        
    
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('rekap/bkm', $data);
        $this->load->view('template/footer');
    }
    


    public function details_pegawai($id_peg, $bulan = 0, $tahun = 0)
{
    $data['title'] = 'Details Kegiatan Pegawai';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

    $bulan = (int) $bulan;
    $tahun = (int) $tahun;

    // Logika label periode seperti details_mitra
    $nama_bulan = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    if ($bulan === 0 && $tahun === 0) {
        $label_periode = "Semua Bulan Semua Tahun";
    } elseif ($bulan === 0) {
        $label_periode = "Semua Bulan Tahun " . $tahun;
    } else {
        $label_periode = $nama_bulan[$bulan - 1] . " " . $tahun;
    }

    $bulan_filter = ($bulan > 0) 
        ? "AND MONTH(FROM_UNIXTIME(kegiatan.finish)) = $bulan" 
        : "";

    $tahun_filter = ($tahun > 0) 
        ? "AND YEAR(FROM_UNIXTIME(kegiatan.finish)) = $tahun" 
        : "";

    $sql = "SELECT all_kegiatan_pengawas.*, kegiatan.nama as namakeg, kegiatan.start, kegiatan.finish, pegawai.nama as pegawainama 
            FROM all_kegiatan_pengawas 
            INNER JOIN kegiatan ON all_kegiatan_pengawas.kegiatan_id = kegiatan.id 
            INNER JOIN pegawai ON pegawai.id_peg = $id_peg  
            WHERE all_kegiatan_pengawas.id_pengawas = $id_peg 
            $bulan_filter $tahun_filter
            ORDER BY kegiatan.start";

    $data['details'] = $this->db->query($sql)->result_array();
    $data['pegawai'] = $this->db->get_where('pegawai', ['id_peg' => $id_peg])->row_array();
    $data['selected_bulan'] = $bulan;
    $data['selected_tahun'] = $tahun;
    $data['label_periode'] = $label_periode;

    $this->load->view('template/header', $data);
    $this->load->view('template/sidebar', $data);
    $this->load->view('template/topbar', $data);
    $this->load->view('rekap/details-bkp', $data);
    $this->load->view('template/footer');
}

    public function details_mitra($id_mitra, $bulan = 0, $tahun = null)
{
    $data['title'] = 'Details Kegiatan Mitra';

    $data['user'] = $this->db->get_where('user', [
        'email' => $this->session->userdata('email')
    ])->row_array();

    $bulan = (int) $bulan;
    $tahun = $tahun ?? date('Y');

    $bulan_filter_pengawas = ($bulan > 0) 
    ? "AND MONTH(FROM_UNIXTIME(keg.finish)) = $bulan" 
    : "";

    $tahun_filter_pengawas = ($tahun > 0) 
        ? "AND YEAR(FROM_UNIXTIME(keg.finish)) = $tahun" 
        : "";

    $bulan_filter_pencacah = ($bulan > 0) 
        ? "AND MONTH(FROM_UNIXTIME(keg.finish)) = $bulan" 
        : "";

    $tahun_filter_pencacah = ($tahun > 0) 
        ? "AND YEAR(FROM_UNIXTIME(keg.finish)) = $tahun" 
        : "";

    $sql = "
(
    SELECT
        keg.id AS kegiatan_id,
        keg.nama AS namakeg,
        keg.start,
        keg.finish,
        mit.nama AS mitranama,
        mit.id_mitra,
        'pengawas' AS peran,
        rk.ob,
        sp.nama AS sistem_pembayaran,
        rk.beban,
        rk.honor,
        rk.total_honor
    FROM all_kegiatan_pengawas akp
    JOIN kegiatan keg ON akp.kegiatan_id = keg.id
    JOIN mitra mit ON mit.id_mitra = akp.id_pengawas
    LEFT JOIN rinciankegiatan rk 
        ON rk.kegiatan_id = keg.id AND rk.id_mitra = akp.id_pengawas
    LEFT JOIN sistempembayaran sp 
        ON rk.ob = sp.kode
    WHERE akp.id_pengawas = $id_mitra
    $bulan_filter_pengawas
    $tahun_filter_pengawas
)
UNION ALL
(
    SELECT
        keg.id AS kegiatan_id,
        keg.nama AS namakeg,
        keg.start,
        keg.finish,
        mit.nama AS mitranama,
        mit.id_mitra,
        'pencacah' AS peran,
        rk.ob,
        sp.nama AS sistem_pembayaran,
        rk.beban,
        rk.honor,
        rk.total_honor
    FROM all_kegiatan_pencacah ak
    JOIN kegiatan keg ON ak.kegiatan_id = keg.id
    JOIN mitra mit ON mit.id_mitra = ak.id_mitra
    LEFT JOIN rinciankegiatan rk 
        ON rk.kegiatan_id = keg.id AND rk.id_mitra = ak.id_mitra
    LEFT JOIN sistempembayaran sp 
        ON rk.ob = sp.kode
    WHERE ak.id_mitra = $id_mitra
    $bulan_filter_pencacah
    $tahun_filter_pencacah
)
ORDER BY start ASC
";

    $details = $this->db->query($sql)->result_array();

    foreach ($details as &$d) {
        $label = ($d['peran'] == 'pengawas') ? ' (PML)' : ' (PPL)';
        $d['namakeg'] .= $label;
    }

    $data['details'] = $details;
    $data['mitra'] = $this->db->get_where('mitra', ['id_mitra' => $id_mitra])->row_array();
    $data['selected_bulan'] = $bulan;
    $data['selected_tahun'] = $tahun;

    $this->load->view('template/header', $data);
    $this->load->view('template/sidebar', $data);
    $this->load->view('template/topbar', $data);
    $this->load->view('rekap/details-bkm', $data);
    $this->load->view('template/footer');
}



public function filter_periode($bulan = 0, $tahun = 0)
{
    $data['title'] = 'Beban Kerja Mitra';

    // Ambil data user login
    $data['user'] = $this->db->get_where('user', [
        'email' => $this->session->userdata('email')
    ])->row_array();

    // Cast ke integer
    $bulan = (int) $bulan;
    $tahun = (int) $tahun;

    // Atur label periode dan logika default tahun
    if ($bulan === 0 && $tahun === 0) {
        // Semua tahun dan semua bulan
        $label_periode = "Semua Tahun Semua Bulan";
    } else {
        // Jika tahun kosong tapi ada bulan, set tahun sekarang
        if ($tahun === 0) {
            $tahun = date('Y');
        }

        $nama_bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                       'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        if ($bulan === 0) {
            // Semua bulan di tahun tertentu
            $label_periode = "Semua Bulan Tahun " . $tahun;
        } else {
            // Bulan dan tahun spesifik
            $label_periode = $nama_bulan[$bulan - 1] . " " . $tahun;
        }
    }

    // --- Logic ambil data rekap mitra (sama seperti sebelumnya) ---
    $mitras = $this->db->get('mitra')->result_array();
    $rekap = [];

    foreach ($mitras as $m) {
        $id_mitra = $m['id_mitra'];
        $nama_mitra = $m['nama'];

        $this->db->from('all_kegiatan_pengawas');
        $this->db->join('kegiatan', 'kegiatan.id = all_kegiatan_pengawas.kegiatan_id');
        $this->db->where('all_kegiatan_pengawas.id_pengawas', $id_mitra);
        if ($bulan > 0) {
            $this->db->where("MONTH(FROM_UNIXTIME(kegiatan.finish)) =", $bulan);
        }
        $this->db->where("YEAR(FROM_UNIXTIME(kegiatan.finish)) =", $tahun);
        $jumlah_pengawasan = $this->db->count_all_results();

        $this->db->from('all_kegiatan_pencacah');
        $this->db->join('kegiatan', 'kegiatan.id = all_kegiatan_pencacah.kegiatan_id');
        $this->db->where('all_kegiatan_pencacah.id_mitra', $id_mitra);
        if ($bulan > 0) {
            $this->db->where("MONTH(FROM_UNIXTIME(kegiatan.finish)) =", $bulan);
        }
        $this->db->where("YEAR(FROM_UNIXTIME(kegiatan.finish)) =", $tahun);
        
        $jumlah_pencacahan = $this->db->count_all_results();

        $rekap[] = [
            'id_mitra' => $id_mitra,
            'nama'     => $nama_mitra,
            'jk'       => $jumlah_pengawasan,
            'jk_p'     => $jumlah_pencacahan
        ];
    }

    // Ambil data rekap dari model
    // Model harus bisa handle filter bulan dan tahun, 
    // misal: 0 bulan = semua bulan, 0 tahun = semua tahun
    $data['rekap'] = $this->Rekap_model->get_rekap_mitra($bulan, $tahun);

    // Simpan pilihan di data untuk view
    $data['selected_bulan'] = $bulan;
    $data['selected_tahun'] = $tahun;
    $data['label_periode'] = $label_periode;

    // Load view dengan data
    $this->load->view('template/header', $data);
    $this->load->view('template/sidebar', $data);
    $this->load->view('template/topbar', $data);
    $this->load->view('rekap/bkm', $data);
    $this->load->view('template/footer');
}

public function filter_periode_pegawai($bulan = 0, $tahun = 0)
{
    $data['title'] = 'Rekap Pegawai';

    // Ambil user dari session
    $data['user'] = $this->db->get_where('user', [
        'email' => $this->session->userdata('email')
    ])->row_array();

    // Cast parameter ke integer
    $bulan = (int) $bulan;
    $tahun = (int) $tahun;

    // Siapkan array nama bulan
    $nama_bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                   'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    // Atur label periode
    if ($bulan === 0 && $tahun === 0) {
        $label_periode = "Semua Tahun Semua Bulan";
    } elseif ($bulan === 0) {
        $label_periode = "Semua Bulan Tahun " . $tahun;
    } elseif ($tahun === 0) {
        $tahun = date('Y'); // Default ke tahun sekarang
        $label_periode = $nama_bulan[$bulan - 1] . " " . $tahun;
    } else {
        $label_periode = $nama_bulan[$bulan - 1] . " " . $tahun;
    }

    // Ambil semua pegawai
    $pegawais = $this->db->get('pegawai')->result_array();

    $rekap = [];

    foreach ($pegawais as $p) {
        $id_peg = $p['id_peg'];
        $nama_peg = $p['nama'];

        // ----------- Hitung jumlah pengawasan ----------
        $this->db->from('all_kegiatan_pengawas');
        $this->db->join('kegiatan', 'kegiatan.id = all_kegiatan_pengawas.kegiatan_id');
        $this->db->where('all_kegiatan_pengawas.id_pengawas', $id_peg);
        if ($bulan > 0) {
            $this->db->where("MONTH(FROM_UNIXTIME(kegiatan.finish)) =", $bulan);
        }
        if ($tahun > 0) {
            $this->db->where("YEAR(FROM_UNIXTIME(kegiatan.finish)) =", $tahun);
        }
        $jumlah_pengawasan = $this->db->count_all_results();

        // ----------- Hitung jumlah pencacahan/pengolahan ----------
        $this->db->from('all_kegiatan_pencacah');
        $this->db->join('kegiatan', 'kegiatan.id = all_kegiatan_pencacah.kegiatan_id');
        $this->db->where('all_kegiatan_pencacah.id_mitra', $id_peg);
        if ($bulan > 0) {
            $this->db->where("MONTH(FROM_UNIXTIME(kegiatan.finish)) =", $bulan);
        }
        if ($tahun > 0) {
            $this->db->where("YEAR(FROM_UNIXTIME(kegiatan.finish)) =", $tahun);
        }
        $jumlah_pencacahan = $this->db->count_all_results();

        $rekap[] = [
            'id_peg' => $id_peg,
            'nama'   => $nama_peg,
            'jk'     => $jumlah_pengawasan,
            'jk_p'   => $jumlah_pencacahan
        ];
    }

    // Data untuk view
    $data['rekap'] = $rekap;
    $data['label_periode'] = $label_periode;
    $data['selected_bulan'] = $bulan;
    $data['selected_tahun'] = $tahun;

    // Tampilkan view
    $this->load->view('template/header', $data);
    $this->load->view('template/sidebar', $data);
    $this->load->view('template/topbar', $data);
    $this->load->view('rekap/bkp', $data);
    $this->load->view('template/footer');
}


public function detail_kegiatan($id_mitra)
{
    $data['title'] = 'Details Kegiatan Mitra';

    $data['user'] = $this->db->get_where('user', [
        'email' => $this->session->userdata('email')
    ])->row_array();

    $data['mitra'] = $this->db->get_where('mitra', ['id_mitra' => $id_mitra])->row_array();
    $data['rincians'] = $this->Penilaian_model->getRincianKegiatanMitra($id_mitra);

    $this->load->view('template/header', $data);
    $this->load->view('template/sidebar', $data);
    $this->load->view('template/topbar', $data);
    $this->load->view('penilaian/detail_kegiatan', $data); // view baru
    $this->load->view('template/footer');
}

public function editdetailbkm($id_mitra, $id_kegiatan)
{
    $data['title'] = 'Edit Detail Beban Kerja Mitra';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

    // Ambil rincian berdasarkan id_mitra dan id_kegiatan
    $rinci = $this->db->get_where('rinciankegiatan', [
        'id_mitra' => $id_mitra,
        'kegiatan_id' => $id_kegiatan
    ])->row_array();

    if (!$rinci) {
        // Jika belum ada, buat data kosong
        $rinci = [
            'id' => null,
            'id_mitra' => $id_mitra,
            'kegiatan_id' => $id_kegiatan,
            'sistem_pembayaran' => '',
            'beban' => 0,
            'honor' => 0,
            'total_honor' => 0
        ];
    }

    // Ambil data mitra
    $data['mitra'] = $this->db->get_where('mitra', ['id_mitra' => $id_mitra])->row_array();

    $data['rinci'] = $rinci;
    $data['id_kegiatan'] = $id_kegiatan;
    $data['id_mitra'] = $id_mitra;
    $data['sistem_pembayaran_list'] = $this->db->get('sistempembayaran')->result_array();


    $this->load->view('template/header', $data);
    $this->load->view('template/sidebar', $data);
    $this->load->view('template/topbar', $data);
    $this->load->view('rekap/edit-details-bkm', $data);
    $this->load->view('template/footer');
}



public function updatedetailbkm()
{
    $id_mitra = $this->input->post('id_mitra');
    $id_kegiatan = $this->input->post('id_kegiatan');
    $sistem = $this->input->post('sistem_pembayaran');
    $beban = (int)$this->input->post('beban');
    $honor = (int)$this->input->post('honor');
    $total = $beban * $honor;

    // Cek apakah sudah ada record di tabel
    $existing = $this->db->get_where('rinciankegiatan', [
        'id_mitra' => $id_mitra,
        'kegiatan_id' => $id_kegiatan
    ])->row_array();

    if ($existing) {
        // update
        $this->db->where(['id_mitra' => $id_mitra, 'kegiatan_id' => $id_kegiatan]);
        $this->db->update('rinciankegiatan', [
            'ob' => $sistem,
            'beban' => $beban,
            'honor' => $honor,
            'total_honor' => $total
        ]);
    } else {
        // insert baru
        $this->db->insert('rinciankegiatan', [
            'id_mitra' => $id_mitra,
            'kegiatan_id' => $id_kegiatan,
            'ob' => $sistem,
            'beban' => $beban,
            'honor' => $honor,
            'total_honor' => $total
        ]);
    }

    $this->session->set_flashdata('message', '<div class="alert alert-success">Data berhasil disimpan!</div>');
    redirect('rekap/details_mitra/' . $id_mitra);
}




}
