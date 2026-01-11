<?php
defined('BASEPATH') or exit('No direct script access allowed');



class Rekap extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Suppress deprecation warnings for PHPExcel on PHP 8+
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);

        is_logged_in();
        $this->load->model('Rekap_model');
        $this->load->model('Kegiatan_model');
        $this->load->model('Penilaian_model');
        $this->load->library('user_agent');
    }

    public function index()
    {
        $this->output->cache(1);
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
        $this->output->cache(1);
        $data['title'] = 'Beban Kerja Pegawai';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // Inisialisasi bulan dan tahun default
        $selected_bulan = 0;
        $selected_tahun = 0;

        $nama_bulan = [
            'Semua Bulan',
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
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
        $this->output->cache(1);
        $data['title'] = 'Beban Kerja Mitra';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // Ambil filter bulan dan tahun dari GET
        $selected_bulan = (int) ($this->input->get('bulan') ?? 0);
        $selected_tahun = (int) ($this->input->get('tahun') ?? 0);

        // Nama bulan
        $nama_bulan = [
            0 => 'Semua Bulan',
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        // Label periode
        $label_periode = ($nama_bulan[$selected_bulan] ?? 'Semua Bulan') . ' ' . ($selected_tahun == 0 ? 'Semua Tahun' : $selected_tahun);
        $data['selected_bulan'] = $selected_bulan;
        $data['selected_tahun'] = $selected_tahun;
        $data['label_periode'] = $label_periode;

        // Rekap kegiatan mitra
        // Rekap kegiatan mitra
        $data['rekap'] = $this->Rekap_model->get_rekap_mitra($selected_bulan, $selected_tahun);

        // Inisialisasi mitra overlimit
        $data['mitra_overlimit'] = [];

        // Proses honor jika filter valid
        if ($selected_bulan > 0 && $selected_tahun > 0) {
            $this->load->model('Rekap_model');
            $rekap_honor = $this->Rekap_model->getRekapTotalMitra($selected_bulan, $selected_tahun);

            // Batas honor tiap posisi
            $batas_honor = [
                'total_ppl' => ['label' => 'PPL', 'batas' => 4624000],
                'total_pml' => ['label' => 'PML', 'batas' => 5120000],
                'total_pos1' => ['label' => 'Pendataan Survei', 'batas' => 3303000],
                'total_pos2' => ['label' => 'Pengolahan Survei', 'batas' => 3056000],
                'total_pos4' => ['label' => 'Pengolahan Sensus', 'batas' => 3386000],
            ];

            $overlimit = [];

            foreach ($rekap_honor as $r) {
                foreach ($batas_honor as $kolom => $info) {
                    if ($r->$kolom > $info['batas']) {
                        $label_posisi = $info['label'];

                        // Khusus posisi 3: ubah label menjadi "Pendataan Sensus (PPL/PML)"
                        if ($kolom === 'total_ppl') {
                            $label_posisi = 'Pendataan Sensus (PPL)';
                        } elseif ($kolom === 'total_pml') {
                            $label_posisi = 'Pendataan Sensus (PML)';
                        }

                        $overlimit[] = [
                            'id_mitra' => $r->id_mitra,
                            'nama' => $r->nama_mitra,
                            'posisi' => $label_posisi,
                            'total' => $r->$kolom,
                            'batas' => $info['batas']
                        ];
                    }
                }
            }

            $data['mitra_overlimit'] = $overlimit;

            // Data untuk Chart Pie (Status Beban Kerja)
            $total_active = count($rekap_honor);
            $total_overload = count($overlimit);
            $total_safe = max(0, $total_active - $total_overload);

            $data['chart_status'] = [
                'overload' => $total_overload,
                'safe' => $total_safe
            ];
        }

        // Load tampilan
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('rekap/bkm', $data); // View yang benar
        $this->load->view('template/footer');
    }

    public function riwayat_mitra($id_mitra)
    {
        $this->output->cache(1);
        $data['title'] = 'Riwayat Tahunan Mitra';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->load->model('Rekap_model');
        $data['mitra'] = $this->db->get_where('mitra', ['id_mitra' => $id_mitra])->row_array();
        $data['riwayat'] = $this->Rekap_model->getRiwayatMitra($id_mitra);

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('rekap/riwayat_mitra', $data);
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
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
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

    public function details_mitra($id_mitra, $bulan = 0, $tahun = null, $auto_insert = true)
    {
        $this->output->cache(1);
        $data['title'] = 'Details Kegiatan Mitra';
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email')
        ])->row_array();
        $data['posisi_list'] = $this->db->get('posisi')->result_array();

        $bulan = (int) $bulan;
        // If tahun is null, default to current year. If it is '0', keep it as 0 (All Years).
        $tahun = (is_null($tahun)) ? date('Y') : (int) $tahun;
        $bulan_filter = ($bulan > 0) ? "AND MONTH(FROM_UNIXTIME(keg.finish)) = $bulan" : "";
        $tahun_filter = ($tahun > 0) ? "AND YEAR(FROM_UNIXTIME(keg.finish)) = $tahun" : "";

        // Cek apakah mitra dialokasikan sebagai pencacah/pengawas
        $has_allocation = $this->db->where('id_pengawas', $id_mitra)->count_all_results('all_kegiatan_pengawas') > 0
            || $this->db->where('id_mitra', $id_mitra)->count_all_results('all_kegiatan_pencacah') > 0;

        if (!$has_allocation) {
            $all_kegiatan = [];
        } else {
            // Ambil kegiatan hanya jika dialokasikan
            $sql = "
        (
            SELECT keg.id AS kegiatan_id, keg.*, mit.nama AS mitranama, mit.id_mitra, 'pengawas' AS peran
            FROM all_kegiatan_pengawas akp
            JOIN mitra mit ON mit.id_mitra = akp.id_pengawas
            JOIN kegiatan keg ON akp.kegiatan_id = keg.id
            WHERE akp.id_pengawas = $id_mitra $bulan_filter $tahun_filter
        )
        UNION ALL
        (
            SELECT keg.id AS kegiatan_id, keg.*, mit.nama AS mitranama, mit.id_mitra, 'pencacah' AS peran
            FROM all_kegiatan_pencacah ak
            JOIN mitra mit ON mit.id_mitra = ak.id_mitra
            JOIN kegiatan keg ON ak.kegiatan_id = keg.id
            WHERE ak.id_mitra = $id_mitra $bulan_filter $tahun_filter
        )
        ORDER BY start ASC
        ";

            $all_kegiatan = $this->db->query($sql)->result_array();
        }

        // Auto-insert has been moved to real-time sync in Kegiatan controller

        // Query final untuk menampilkan semua info lengkap
        $sql_join = "
    SELECT
        keg.id AS kegiatan_id,
        keg.nama AS namakeg,
        keg.jenis_kegiatan,
        keg.start,
        keg.finish,
        mit.nama AS mitranama,
        mit.id_mitra,
        rk.ob,
        sp.nama AS sistem_pembayaran,
        rk.beban,
        rk.honor,
        rk.total_honor,
        rk.posisi AS posisi_id,
        p.posisi AS posisi_nama,
        s.nama AS nama_seksi,
        sa.satuan AS satuan_nama,
        d.peran
    FROM (
        SELECT kegiatan_id, 'pengawas' AS peran FROM all_kegiatan_pengawas WHERE id_pengawas = $id_mitra
        UNION
        SELECT kegiatan_id, 'pencacah' AS peran FROM all_kegiatan_pencacah WHERE id_mitra = $id_mitra
    ) d
    JOIN kegiatan keg ON keg.id = d.kegiatan_id
    $bulan_filter
    $tahun_filter
    JOIN mitra mit ON mit.id_mitra = $id_mitra
    LEFT JOIN rinciankegiatan rk ON rk.kegiatan_id = keg.id AND rk.id_mitra = $id_mitra
    LEFT JOIN sistempembayaran sp ON rk.ob = sp.kode
    LEFT JOIN posisi p ON rk.posisi = p.id
    LEFT JOIN seksi s ON keg.seksi_id = s.id
    LEFT JOIN satuan sa ON rk.satuan = sa.id
    ORDER BY keg.start ASC
    ";

        $details = $this->db->query($sql_join)->result_array();

        $total_posisi_1 = $total_posisi_2 = $total_posisi_3_ppl = $total_posisi_3_pml = $total_posisi_4 = 0;

        foreach ($details as &$d) {
            $d['namakeg'] .= ($d['peran'] === 'pengawas') ? ' (PML)' : ' (PPL)';
            $pos_id = (int) ($d['posisi_id'] ?? 0);
            $total_honor = (int) ($d['total_honor'] ?? 0);

            switch ($pos_id) {
                case 1:
                    $total_posisi_1 += $total_honor;
                    break;
                case 2:
                    $total_posisi_2 += $total_honor;
                    break;
                case 3:
                    if ($d['peran'] === 'pencacah') {
                        $total_posisi_3_ppl += $total_honor;
                    } else {
                        $total_posisi_3_pml += $total_honor;
                    }
                    break;
                case 4:
                    $total_posisi_4 += $total_honor;
                    break;
            }
        }

        $batas_honor = [
            1 => 3303000,
            2 => 3056000,
            3 => ['PPL' => 4624000, 'PML' => 5120000],
            4 => 3386000
        ];

        $data['details'] = $details;
        $data['mitra'] = $this->db->get_where('mitra', ['id_mitra' => $id_mitra])->row_array();
        $data['selected_bulan'] = $bulan;
        $data['selected_tahun'] = $tahun;
        $data['total_posisi_1'] = $total_posisi_1;
        $data['total_posisi_2'] = $total_posisi_2;
        $data['total_posisi_3_ppl'] = $total_posisi_3_ppl;
        $data['total_posisi_3_pml'] = $total_posisi_3_pml;
        $data['total_posisi_4'] = $total_posisi_4;
        $data['status_overlimit'] = [
            'PPL' => $total_posisi_3_ppl > $batas_honor[3]['PPL'],
            'PML' => $total_posisi_3_pml > $batas_honor[3]['PML'],
            'POS1' => $total_posisi_1 > $batas_honor[1],
            'POS2' => $total_posisi_2 > $batas_honor[2],
            'POS4' => $total_posisi_4 > $batas_honor[4],
        ];

        // Data Summary Tahunan (user request)
        $this->load->model('Rekap_model');
        $riwayat = $this->Rekap_model->getRiwayatMitra($id_mitra);
        $annual_summary = ['total_honor' => 0, 'jumlah_kegiatan' => 0];

        foreach ($riwayat as $r) {
            if ($r['tahun'] == $tahun) {
                $annual_summary = $r;
                break;
            }
        }
        $data['annual_summary'] = $annual_summary;

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

        $bulan = (int) $bulan;
        $tahun = (int) $tahun;

        // Atur label periode
        $nama_bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $label_periode = ($bulan === 0 ? 'Semua Bulan' : $nama_bulan[$bulan]) . ' ' . ($tahun === 0 ? 'Semua Tahun' : $tahun);

        // Rekap jumlah kegiatan pengawas & pencacah
        $mitras = $this->db->get('mitra')->result_array();
        $rekap = [];

        foreach ($mitras as $m) {
            $id_mitra = $m['id_mitra'];
            $nama_mitra = $m['nama'];

            // Pengawasan
            $this->db->from('all_kegiatan_pengawas');
            $this->db->join('kegiatan', 'kegiatan.id = all_kegiatan_pengawas.kegiatan_id');
            $this->db->where('all_kegiatan_pengawas.id_pengawas', $id_mitra);
            if ($bulan > 0) {
                $this->db->where("MONTH(FROM_UNIXTIME(kegiatan.finish)) =", $bulan);
            }
            if ($tahun > 0) {
                $this->db->where("YEAR(FROM_UNIXTIME(kegiatan.finish)) =", $tahun);
            }
            $jumlah_pengawasan = $this->db->count_all_results();

            // Pencacahan
            $this->db->from('all_kegiatan_pencacah');
            $this->db->join('kegiatan', 'kegiatan.id = all_kegiatan_pencacah.kegiatan_id');
            $this->db->where('all_kegiatan_pencacah.id_mitra', $id_mitra);
            if ($bulan > 0) {
                $this->db->where("MONTH(FROM_UNIXTIME(kegiatan.finish)) =", $bulan);
            }
            if ($tahun > 0) {
                $this->db->where("YEAR(FROM_UNIXTIME(kegiatan.finish)) =", $tahun);
            }
            $jumlah_pencacahan = $this->db->count_all_results();

            $rekap[] = [
                'id_mitra' => $id_mitra,
                'nama' => $nama_mitra,
                'jk' => $jumlah_pengawasan,
                'jk_p' => $jumlah_pencacahan
            ];
        }

        $data['rekap'] = $rekap;

        // Validasi honor overload
        $this->load->model('Rekap_model');
        $rekap_honor = $this->Rekap_model->getRekapTotalMitra($bulan, $tahun);

        // Definisi batas honor per posisi
        $batas_honor = [
            'total_ppl' => ['label' => 'PPL', 'batas' => 4624000],
            'total_pml' => ['label' => 'PML', 'batas' => 5120000],
            'total_pos1' => ['label' => 'Pendataan Survei', 'batas' => 3303000],
            'total_pos2' => ['label' => 'Pengolahan Survei', 'batas' => 3056000],
            'total_pos4' => ['label' => 'Pengolahan Sensus', 'batas' => 3386000],
        ];

        $overlimit = [];

        foreach ($rekap_honor as $r) {
            foreach ($batas_honor as $kolom => $info) {
                if ($r->$kolom > $info['batas']) {
                    $label_posisi = $info['label'];

                    // Khusus posisi 3: ubah label menjadi "Pendataan Sensus (PPL/PML)"
                    if ($kolom === 'total_ppl') {
                        $label_posisi = 'Pendataan Sensus (PPL)';
                    } elseif ($kolom === 'total_pml') {
                        $label_posisi = 'Pendataan Sensus (PML)';
                    }

                    $overlimit[] = [
                        'id_mitra' => $r->id_mitra,
                        'nama' => $r->nama_mitra,
                        'posisi' => $label_posisi,
                        'total' => $r->$kolom,
                        'batas' => $info['batas']
                    ];
                }
            }
        }

        $data['mitra_overlimit'] = $overlimit;

        // Tambahan untuk view
        $data['selected_bulan'] = $bulan;
        $data['selected_tahun'] = $tahun;
        $data['label_periode'] = $label_periode;

        // Load view
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
        $nama_bulan = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

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
                'nama' => $nama_peg,
                'jk' => $jumlah_pengawasan,
                'jk_p' => $jumlah_pencacahan
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
                'posisi' => '',
                'satuan' => '',
                'beban' => 0,
                'honor' => 0,
                'total_honor' => 0
            ];
        }

        // Ambil data mitra
        $data['mitra'] = $this->db->get_where('mitra', ['id_mitra' => $id_mitra])->row_array();

        // Ambil data kegiatan
        $kegiatan = $this->db->get_where('kegiatan', ['id' => $id_kegiatan])->row_array();
        $data['kegiatan'] = $kegiatan;

        $finish = isset($kegiatan['finish']) ? (int) $kegiatan['finish'] : time();

        $data['bulan'] = date('n', $finish);
        $data['tahun'] = date('Y', $finish);

        // Ambil referensi sistem pembayaran, posisi, dan satuan
        $data['sistem_pembayaran_list'] = $this->db->get('sistempembayaran')->result_array();
        $data['posisi_list'] = $this->db->get('posisi')->result_array();
        $data['satuan_list'] = $this->db->get('satuan')->result_array();

        // Kirim data ke view
        $data['rinci'] = $rinci;
        $data['id_kegiatan'] = $id_kegiatan;
        $data['id_mitra'] = $id_mitra;

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
        $posisi = $this->input->post('posisi');
        $satuan = $this->input->post('satuan');
        $beban = (int) $this->input->post('beban');
        $honor = (int) $this->input->post('honor');
        $total = $beban * $honor;

        // Ambil data dari tabel kegiatan
        $kegiatan = $this->db->get_where('kegiatan', ['id' => $id_kegiatan])->row_array();
        $start = $kegiatan['start'] ?? time();
        $finish = $kegiatan['finish'] ?? time();
        $seksi_id = $kegiatan['seksi_id'] ?? null;

        // Validasi & update/insert
        $existing = $this->db->get_where('rinciankegiatan', [
            'id_mitra' => $id_mitra,
            'kegiatan_id' => $id_kegiatan
        ])->row_array();

        $data_update = [
            'ob' => $sistem,
            'posisi' => $posisi,
            'satuan' => $satuan,
            'beban' => $beban,
            'honor' => $honor,
            'total_honor' => $total,
            'start' => $start,
            'finish' => $finish,
            'seksi_id' => $seksi_id
        ];

        if ($existing) {
            $this->db->where(['id_mitra' => $id_mitra, 'kegiatan_id' => $id_kegiatan]);
            $this->db->update('rinciankegiatan', $data_update);
        } else {
            $data_insert = array_merge($data_update, [
                'id_mitra' => $id_mitra,
                'kegiatan_id' => $id_kegiatan
            ]);
            $this->db->insert('rinciankegiatan', $data_insert);
        }

        // Redirect dengan parameter bulan/tahun dari tanggal finish kegiatan
        $bulan = date('n', $finish);
        $tahun = date('Y', $finish);

        $this->session->set_flashdata('message', '<div class="alert alert-success">Data berhasil disimpan!</div>');
        redirect("rekap/details_mitra/$id_mitra/$bulan/$tahun");
    }

    public function export_excel($bulan = null, $tahun = null)
    {
        $bulan = (int) $bulan;
        $tahun = (int) $tahun;

        $this->load->model('Rekap_model');
        $data_detail = $this->Rekap_model->getRekapHonor($bulan, $tahun);
        $data_rekap = $this->Rekap_model->getRekapTotalMitra($bulan, $tahun);

        // Load PHPExcel
        require_once FCPATH . 'assets/phpexcel/Classes/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("BPS")
            ->setLastModifiedBy("BPS")
            ->setTitle("Detail Kegiatan Mitra")
            ->setSubject("Detail Kegiatan Mitra")
            ->setDescription("Detail Kegiatan Mitra");

        // Sheet 1: Detail Honor
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet1 = $objPHPExcel->getActiveSheet();
        $sheet1->setTitle('Detail Kegiatan Mitra');

        // Header Sheet 1
        $headers1 = ['ID Sobat', 'Nama Mitra', 'Peran', 'Nama Kegiatan', 'Start', 'Finish', 'Seksi', 'Posisi', 'Satuan', 'Beban', 'Honor/Satuan', 'Total Honor', 'Paket Data'];
        $col = 'A';
        foreach ($headers1 as $h) {
            $sheet1->setCellValue($col . '1', $h);
            $sheet1->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        // Style Header
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => 'FFFF00']
            ]
        ];
        $sheet1->getStyle('A1:M1')->applyFromArray($headerStyle);

        // Data Sheet 1
        $row1 = 2;
        foreach ($data_detail as $d) {
            $sheet1->setCellValueExplicit('A' . $row1, $d->sobat_id, PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet1->setCellValue('B' . $row1, $d->mitranama);
            $sheet1->setCellValue('C' . $row1, $d->peran);
            $sheet1->setCellValue('D' . $row1, $d->namakeg);
            $sheet1->setCellValue('E' . $row1, date('Y-m-d', $d->start));
            $sheet1->setCellValue('F' . $row1, date('Y-m-d', $d->finish));
            $sheet1->setCellValue('G' . $row1, $d->nama_seksi);
            $sheet1->setCellValue('H' . $row1, $d->posisi_nama);
            $sheet1->setCellValue('I' . $row1, $d->satuan_nama);
            $sheet1->setCellValue('J' . $row1, $d->beban);
            $sheet1->setCellValue('K' . $row1, $d->honor);
            $sheet1->getStyle('K' . $row1)->getNumberFormat()->setFormatCode('"Rp" #,##0');
            $sheet1->setCellValue('L' . $row1, $d->total_honor);
            $sheet1->getStyle('L' . $row1)->getNumberFormat()->setFormatCode('"Rp" #,##0');
            $sheet1->setCellValue('M' . $row1, $d->sistem_pembayaran);
            $row1++;
        }

        // Sheet 2: Rekap Total per Mitra
        $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex(1);
        $sheet2 = $objPHPExcel->getActiveSheet();
        $sheet2->setTitle('Rekap Honor Mitra');

        // Header Sheet 2
        $headers2 = ['ID Sobat', 'Nama Mitra', 'Total Pendataan Survei', 'Total Pengolahan Survei', 'Total PPL Pendataan Sensus', 'Total PML Pendataan Sensus', 'Total Pengolahan Sensus', 'Total Semua Posisi'];
        $col = 'A';
        foreach ($headers2 as $h) {
            $sheet2->setCellValue($col . '1', $h);
            $sheet2->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }
        $sheet2->getStyle('A1:H1')->applyFromArray($headerStyle);

        // Data Sheet 2
        $row2 = 2;
        foreach ($data_rekap as $rekap) {
            $total_semua = $rekap->total_pos1 + $rekap->total_pos2 + $rekap->total_ppl + $rekap->total_pml + $rekap->total_pos4;

            $sheet2->setCellValueExplicit('A' . $row2, $rekap->sobat_id, PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet2->setCellValue('B' . $row2, $rekap->nama_mitra);
            $sheet2->setCellValue('C' . $row2, $rekap->total_pos1);
            $sheet2->getStyle('C' . $row2)->getNumberFormat()->setFormatCode('"Rp" #,##0');

            $sheet2->setCellValue('D' . $row2, $rekap->total_pos2);
            $sheet2->getStyle('D' . $row2)->getNumberFormat()->setFormatCode('"Rp" #,##0');

            $sheet2->setCellValue('E' . $row2, $rekap->total_ppl);
            $sheet2->getStyle('E' . $row2)->getNumberFormat()->setFormatCode('"Rp" #,##0');

            $sheet2->setCellValue('F' . $row2, $rekap->total_pml);
            $sheet2->getStyle('F' . $row2)->getNumberFormat()->setFormatCode('"Rp" #,##0');

            $sheet2->setCellValue('G' . $row2, $rekap->total_pos4);
            $sheet2->getStyle('G' . $row2)->getNumberFormat()->setFormatCode('"Rp" #,##0');

            $sheet2->setCellValue('H' . $row2, $total_semua);
            $sheet2->getStyle('H' . $row2)->getNumberFormat()->setFormatCode('"Rp" #,##0');
            $row2++;
        }

        // Tambahkan baris total
        $sheet2->setCellValue('B' . $row2, 'TOTAL');
        $sheet2->setCellValue('C' . $row2, '=SUM(C2:C' . ($row2 - 1) . ')');
        $sheet2->setCellValue('D' . $row2, '=SUM(D2:D' . ($row2 - 1) . ')');
        $sheet2->setCellValue('E' . $row2, '=SUM(E2:E' . ($row2 - 1) . ')');
        $sheet2->setCellValue('F' . $row2, '=SUM(F2:F' . ($row2 - 1) . ')');
        $sheet2->setCellValue('G' . $row2, '=SUM(G2:G' . ($row2 - 1) . ')');
        $sheet2->setCellValue('H' . $row2, '=SUM(H2:H' . ($row2 - 1) . ')');

        $styleArray = [
            'font' => ['bold' => true],
            'borders' => ['top' => ['borderStyle' => PHPExcel_Style_Border::BORDER_THIN]],
        ];
        $sheet2->getStyle("B{$row2}:H{$row2}")->applyFromArray($styleArray);

        // Sheet 3: Rekap Tahunan Per Mitra
        // Note: getRekapTahunanPerMitra logic is complex, assuming we want to include it if available
        $data_tahunan = $this->Rekap_model->getRekapTahunanPerMitra($tahun);

        $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex(2);
        $sheet3 = $objPHPExcel->getActiveSheet();
        $sheet3->setTitle('Rekap Tahunan Mitra');

        $headers3 = ['ID Sobat', 'Nama Mitra', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des', 'Total'];
        $col = 'A';
        foreach ($headers3 as $h) {
            $sheet3->setCellValue($col . '1', $h);
            $sheet3->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }
        $sheet3->getStyle('A1:O1')->applyFromArray($headerStyle);

        $row3 = 2;
        foreach ($data_tahunan as $rekap) {
            $sheet3->setCellValueExplicit("A{$row3}", $rekap->sobat_id, PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet3->setCellValue("B{$row3}", $rekap->nama_mitra);

            for ($i = 1; $i <= 12; $i++) {
                $colIndex = PHPExcel_Cell::stringFromColumnIndex($i + 1); // $i=1 -> C (index 2)
                $nilai = $rekap->bulanan[$i] ?? 0;
                $sheet3->setCellValue("{$colIndex}{$row3}", $nilai);
                $sheet3->getStyle("{$colIndex}{$row3}")->getNumberFormat()->setFormatCode('"Rp" #,##0');
            }

            $sheet3->setCellValue("O{$row3}", "=SUM(C{$row3}:N{$row3})");
            $sheet3->getStyle("O{$row3}")->getNumberFormat()->setFormatCode('"Rp" #,##0');

            $row3++;
        }

        // Total row for Sheet 3
        $sheet3->setCellValue('B' . $row3, 'TOTAL');
        for ($i = 2; $i <= 14; $i++) { // C to O
            $col = PHPExcel_Cell::stringFromColumnIndex($i);
            $sheet3->setCellValue($col . $row3, "=SUM({$col}2:{$col}" . ($row3 - 1) . ")");
        }
        $sheet3->getStyle("B{$row3}:O{$row3}")->applyFromArray($styleArray);


        $filename = 'Rekap_Honor_' . $bulan . '_' . $tahun . '.xlsx';

        // Clean output buffer to prevent ERR_INVALID_RESPONSE
        if (ob_get_length())
            ob_end_clean();

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    public function export_nilai_excel($bulan = null, $tahun = null)
    {
        $this->load->model('Ranking_model');
        $this->load->model('Rekap_model');

        // Ambil data Rekap Nilai Tahunan (Monthly Averages + Total Activities)
        $data_nilai = $this->Rekap_model->getRekapNilaiTahunanSAW($tahun);

        // === SHEET 1 ===
        require_once FCPATH . 'assets/phpexcel/Classes/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("BPS")
            ->setLastModifiedBy("BPS")
            ->setTitle("Rekap Nilai")
            ->setSubject("Rekap Nilai Bulanan")
            ->setDescription("Rekap Nilai Bulanan");

        $objPHPExcel->setActiveSheetIndex(0);
        $sheet1 = $objPHPExcel->getActiveSheet();
        $sheet1->setTitle('Rekap Nilai Bulanan');

        // Headers: Nama Mitra, Jan...Des, Jumlah Kegiatan, Rata-rata Akhir
        $header = ['Nama Mitra'];
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        foreach ($months as $m) {
            $header[] = $m;
        }
        $header[] = 'Jumlah Kegiatan';
        $header[] = 'Rata-rata Akhir';

        $col = 'A';
        foreach ($header as $h) {
            $sheet1->setCellValue($col . '1', $h);
            $sheet1->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        // Apply Header Style
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => 'FFFF00']
            ]
        ];
        $sheet1->getStyle('A1:O1')->applyFromArray($headerStyle); // A to O (1+12+2 columns)

        $row = 2;
        foreach ($data_nilai as $id_mitra => $data) {
            // Skip rows with no activity if needed, or show all. User usually prefers all.
            // But if completely empty, maybe skip? Let's show all for completeness as per requested format

            $sheet1->setCellValue('A' . $row, $data->nama_mitra);

            $colIndex = 1; // Start B
            $total_score_sum = 0;
            $months_with_score = 0;

            // Monthly Scores
            for ($m = 1; $m <= 12; $m++) {
                $score = $data->bulanan[$m];
                $sheet1->setCellValueByColumnAndRow($colIndex, $row, $score);
                $colIndex++;

                if ($score > 0) {
                    $total_score_sum += $score;
                    $months_with_score++;
                    // Note: This average is "Average of Monthly Averages". 
                    // Alternatively we could recalculate total weighted score / total activities.
                    // But usually "Average of Averages" per month is acceptable for this view unless specified.
                    // Actually, let's use the average of the months that have data.
                }
            }

            // Jumlah Kegiatan
            $sheet1->setCellValueByColumnAndRow($colIndex, $row, $data->total_kegiatan);
            $colIndex++;

            // Rata-rata Akhir (Average of months present)
            $rata_akhir = $months_with_score > 0 ? round($total_score_sum / $months_with_score, 2) : 0;
            $sheet1->setCellValueByColumnAndRow($colIndex, $row, $rata_akhir);

            $row++;
        }

        // === SHEET 2: Detail Kegiatan & Peringkat ===
        $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex(1);
        $sheet2 = $objPHPExcel->getActiveSheet();
        $sheet2->setTitle('Detail Kegiatan');

        $headers2 = ['No', 'Nama Mitra', 'Nama Kegiatan', 'Bulan', 'Tahun', 'Nilai', 'Peringkat'];
        $col = 'A';
        foreach ($headers2 as $h) {
            $sheet2->setCellValue($col . '1', $h);
            $sheet2->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }
        $sheet2->getStyle('A1:G1')->applyFromArray($headerStyle);

        // Fetch Kegiatan based on filter
        if ($bulan == 0 && $tahun == 0) {
            $kegiatanQuery = "SELECT id, nama, finish FROM kegiatan ORDER BY finish DESC";
            $kegiatanList = $this->db->query($kegiatanQuery)->result();
        } elseif ($bulan == 0) {
            $kegiatanList = $this->db->query("SELECT id, nama, finish FROM kegiatan WHERE YEAR(FROM_UNIXTIME(finish)) = ? ORDER BY finish DESC", [$tahun])->result();
        } else {
            $kegiatanList = $this->db->query("SELECT id, nama, finish FROM kegiatan WHERE MONTH(FROM_UNIXTIME(finish)) = ? AND YEAR(FROM_UNIXTIME(finish)) = ? ORDER BY finish DESC", [$bulan, $tahun])->result();
        }

        $row = 2;
        $no = 1;

        foreach ($kegiatanList as $keg) {
            // Get Ranking for this activity
            // totalakhir returns sorted list by total score descending
            $rankList = $this->Ranking_model->totalakhir($keg->id);

            $rank = 1;
            $bulan_txt = $months[date('n', $keg->finish) - 1] ?? '-'; // 0-indexed array
            $tahun_txt = date('Y', $keg->finish);

            foreach ($rankList as $r) {
                $sheet2->setCellValue('A' . $row, $no++);
                $sheet2->setCellValue('B' . $row, $r->nama); // Nama Mitra
                $sheet2->setCellValue('C' . $row, $keg->nama); // Nama Kegiatan
                $sheet2->setCellValue('D' . $row, $bulan_txt);
                $sheet2->setCellValue('E' . $row, $tahun_txt);
                $sheet2->setCellValue('F' . $row, round($r->total, 2));
                $sheet2->setCellValue('G' . $row, $rank++); // Peringkat
                $row++;
            }
        }

        $filename = 'rekap_nilai_' . $bulan . '_' . $tahun . '.xlsx';

        // Clean output buffer to prevent ERR_INVALID_RESPONSE
        if (ob_get_length())
            ob_end_clean();

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }



}
