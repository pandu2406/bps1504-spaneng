<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Persuratan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Persuratan_model');
    }

    public function lpd()
    {
        $this->output->cache(1);
        $data['title'] = 'Laporan Perjalanan Dinas';

        // Ambil user login dari session
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email')
        ])->row_array();

        $role_id = $data['user']['role_id'];
        $email = $data['user']['email'];
        $now = time();

        // Query kegiatan sesuai role
        if ($role_id == 5) { // Role Mitra
            $id_mitra_row = $this->db->query("SELECT id_mitra, sobat_id FROM mitra WHERE email = '$email'")->row();
            $id_mitra = $id_mitra_row->id_mitra ?? 0;
            $sobat_id = $id_mitra_row->sobat_id ?? '';

            $sql = "SELECT kegiatan.* FROM kegiatan 
                JOIN all_kegiatan_pencacah ON all_kegiatan_pencacah.kegiatan_id = kegiatan.id 
                WHERE all_kegiatan_pencacah.id_mitra = $id_mitra 
                AND ((kegiatan.start <= $now AND kegiatan.finish >= $now) OR (kegiatan.start > $now))
                ORDER BY kegiatan.start";

            // Hanya tampilkan LPD milik mitra berdasarkan kecocokan sobat_id
            $data['lpd_list'] = $this->db
                ->order_by('id', 'DESC')
                ->get_where('lpd', ['nip' => $sobat_id])
                ->result();

        } elseif ($role_id == 4) { // Role Pengawas Organik
            $id_peg_row = $this->db->query("SELECT id_peg FROM pegawai WHERE email = '$email'")->row();

            if (!$id_peg_row) {
                $id_peg_row = $this->db->query("SELECT id_mitra as id_peg FROM mitra WHERE email = '$email'")->row();
            }

            $id_peg = $id_peg_row->id_peg ?? 0;

            $sql = "SELECT kegiatan.* FROM kegiatan 
                JOIN all_kegiatan_pengawas ON all_kegiatan_pengawas.kegiatan_id = kegiatan.id 
                WHERE all_kegiatan_pengawas.id_pengawas = $id_peg 
                AND ((kegiatan.start <= $now AND kegiatan.finish >= $now) OR (kegiatan.start > $now))
                ORDER BY kegiatan.start";

            $data['lpd_list'] = $this->db->order_by('id', 'DESC')->get('lpd')->result();

        } elseif ($role_id <= 2) { // Admin/Kepala
            $sql = "SELECT * FROM kegiatan 
                WHERE ((start <= $now AND finish >= $now) OR (start > $now)) 
                ORDER BY start";

            $data['lpd_list'] = $this->db->order_by('id', 'DESC')->get('lpd')->result();

        } else { // Role Seksi
            $seksi_id = $data['user']['seksi_id'];
            $sql = "SELECT * FROM kegiatan 
                WHERE seksi_id = $seksi_id 
                AND ((start <= $now AND finish >= $now) OR (start > $now)) 
                ORDER BY start";

            $data['lpd_list'] = $this->db->order_by('id', 'DESC')->get('lpd')->result();
        }

        $data['jlhk'] = $this->db->query($sql)->num_rows();
        $data['kegiatan'] = $this->db->query($sql)->result_array();

        // Load view
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('persuratan/lpd', $data);
        $this->load->view('template/footer');
    }

    public function lpd_pegawai()
    {
        $data['title'] = 'Entry LPD Pegawai';
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email')
        ])->row_array();

        $data['pegawai_list'] = $this->db->get('pegawai')->result_array();

        foreach ($data['pegawai_list'] as &$peg) {
            $peg['nip'] = $this->format_nip($peg['nip']);
        }

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('persuratan/form_lpd_pegawai', $data);
        $this->load->view('template/footer');
    }

    public function lpd_mitra()
    {
        $data['title'] = 'Entry LPD Mitra';
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email')
        ])->row_array();

        // Ambil data dari tabel mitra
        $data['mitra_list'] = $this->db->get('mitra')->result_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('persuratan/form_lpd_mitra', $data); // <-- view form LPD mitra
        $this->load->view('template/footer');
    }

    public function save_lpd_pegawai()
    {
        $post = $this->input->post();

        // Upload multiple dokumentasi
        $config['upload_path'] = './uploads/foto_lpd/';
        $config['allowed_types'] = 'jpg|jpeg|png|heic';
        $config['max_size'] = 2048;
        $this->load->library('upload');

        $uploaded_urls = [];
        $uploaded_paths = [];
        $files = $_FILES['foto'];
        $count = count($files['name']);

        for ($i = 0; $i < $count; $i++) {
            $_FILES['file']['name'] = $files['name'][$i];
            $_FILES['file']['type'] = $files['type'][$i];
            $_FILES['file']['tmp_name'] = $files['tmp_name'][$i];
            $_FILES['file']['error'] = $files['error'][$i];
            $_FILES['file']['size'] = $files['size'][$i];

            $this->upload->initialize($config);
            if ($this->upload->do_upload('file')) {
                $data = $this->upload->data();
                $url = base_url('uploads/foto_lpd/' . $data['file_name']);
                $uploaded_urls[] = $url;
                $uploaded_paths[] = FCPATH . 'uploads/foto_lpd/' . $data['file_name'];
            }
        }

        // Ambil Hari & Tanggal
        $timestamp = strtotime($post['tgl_tugas']);
        $hari = $this->convert_day(date('l', $timestamp));
        $tanggal = $this->format_tanggal_indo($post['tgl_tugas']);
        $haritanggal = "$hari, $tanggal";

        // Rundown rows
        $rows = [];
        for ($i = 0; $i < count($post['waktu_awal']); $i++) {
            $rows[] = [
                'no' => $i + 1,
                'haritanggal' => ($i == 0) ? $haritanggal : '',
                'waktu' => $post['waktu_awal'][$i] . ' – ' . $post['waktu_akhir'][$i],
                'kegiatan' => $post['kegiatanx'][$i],
                'lokasi' => $post['lokasix'][$i],
            ];
        }

        // Generate Word
        $template = new \PhpOffice\PhpWord\TemplateProcessor(APPPATH . 'templates/templateLPD_organik.docx');
        $template->setValue('nama_peg', $post['nama']);
        $template->setValue('nip', $post['nip']);
        $template->setValue('jabatan_peg', $post['jabatan']);
        $template->setValue('nomorST_peg', $post['no_st']);
        $template->setValue('tanggalST_peg', $this->format_tanggal_indo($post['tgl_st']));
        $template->setValue('tanggaltugas_peg', $this->format_tanggal_indo($post['tgl_tugas']));
        $template->setValue('tujuantugas_peg', $post['tujuan_tugas']);
        $template->setValue('resume_peg', $post['resume']);
        $template->setValue('tanggal_buat', $this->format_tanggal_indo($post['tanggal_buat']));

        // Clone rundown
        $template->cloneRowAndSetValues('no', $rows);

        // Tambahkan dokumentasi foto (maks 3)
        for ($i = 0; $i < 3; $i++) {
            $tag = 'foto' . ($i + 1);
            if (isset($uploaded_paths[$i]) && file_exists($uploaded_paths[$i])) {
                $template->setImageValue($tag, [
                    'path' => $uploaded_paths[$i],
                    'width' => 300,
                    'height' => 200
                ]);
            } else {
                $template->setValue($tag, '-');
            }
        }

        // Simpan file Word
        $nama_file = preg_replace('/[^a-zA-Z0-9]/', '_', $post['nama']);
        $tanggal_tugas = date('Ymd', strtotime($post['tgl_tugas']));
        $base_name = $nama_file . '_' . $tanggal_tugas;
        $filename = $base_name . '.docx';
        $savePath = FCPATH . 'uploads/laporan/' . $filename;

        // Cek jika file sudah ada → tambahkan (1), (2), dst
        $counter = 1;
        while (file_exists($savePath)) {
            $filename = $base_name . "($counter).docx";
            $savePath = FCPATH . 'uploads/laporan/' . $filename;
            $counter++;
        }

        $template->saveAs($savePath);

        // Simpan ke DB
        $post['waktu_awal'] = json_encode($post['waktu_awal']);
        $post['waktu_akhir'] = json_encode($post['waktu_akhir']);
        $post['kegiatanx'] = json_encode($post['kegiatanx']);
        $post['lokasix'] = json_encode($post['lokasix']);
        $post['entrian'] = implode("\n", $uploaded_urls);
        $post['nama_file_word'] = $filename;

        $this->db->insert('lpd', $post);

        // Notifikasi berhasil
        $this->session->set_flashdata('success', 'Laporan berhasil disimpan dan file Word berhasil dibuat.');

        // Simpan nama file ke session sementara
        $this->session->set_flashdata('file_generated', $filename);

        // Redirect ke halaman LPD yang akan men-trigger download
        redirect(base_url('persuratan/lpd'));

    }

    public function save_lpd_mitra()
    {
        $post = $this->input->post();

        // Upload dokumentasi foto
        $config['upload_path'] = './uploads/foto_lpd/';
        $config['allowed_types'] = 'jpg|jpeg|png|heic';
        $config['max_size'] = 2048;
        $this->load->library('upload');

        $uploaded_urls = [];
        $uploaded_paths = [];
        $files = $_FILES['foto'];
        $count = count($files['name']);

        for ($i = 0; $i < $count; $i++) {
            $_FILES['file']['name'] = $files['name'][$i];
            $_FILES['file']['type'] = $files['type'][$i];
            $_FILES['file']['tmp_name'] = $files['tmp_name'][$i];
            $_FILES['file']['error'] = $files['error'][$i];
            $_FILES['file']['size'] = $files['size'][$i];

            $this->upload->initialize($config);
            if ($this->upload->do_upload('file')) {
                $data = $this->upload->data();
                $url = base_url('uploads/foto_lpd/' . $data['file_name']);
                $uploaded_urls[] = $url;
                $uploaded_paths[] = FCPATH . 'uploads/foto_lpd/' . $data['file_name'];
            }
        }

        // Ambil Hari & Tanggal
        $timestamp = strtotime($post['tgl_tugas']);
        $hari = $this->convert_day(date('l', $timestamp));
        $tanggal = $this->format_tanggal_indo($post['tgl_tugas']);
        $haritanggal = "$hari, $tanggal";

        // Rundown rows
        $rows = [];
        for ($i = 0; $i < count($post['waktu_awal']); $i++) {
            $rows[] = [
                'no' => $i + 1,
                'haritanggal' => ($i == 0) ? $haritanggal : '',
                'waktu' => $post['waktu_awal'][$i] . ' – ' . $post['waktu_akhir'][$i],
                'kegiatan' => $post['kegiatanx'][$i],
                'lokasi' => $post['lokasix'][$i],
            ];
        }

        // Set jabatan dan jenis untuk mitra
        $post['jabatan'] = 'Mitra Statistik';
        $post['jenis'] = 'mitra';

        // Generate nama file
        $nama_file = preg_replace('/[^a-zA-Z0-9]/', '_', $post['nama']);
        $tanggal_tugas = date('Ymd', strtotime($post['tgl_tugas']));
        $base_name = $nama_file . '_' . $tanggal_tugas;
        $filename = $base_name . '.docx';
        $savePath = FCPATH . 'uploads/laporan/' . $filename;

        // Cek duplikat nama file → tambahkan (1), (2), dst
        $counter = 1;
        while (file_exists($savePath)) {
            $filename = $base_name . "($counter).docx";
            $savePath = FCPATH . 'uploads/laporan/' . $filename;
            $counter++;
        }

        // Simpan nama file ke post untuk DB
        $post['nama_file_word'] = $filename;

        // Simpan data rundown dan entrian ke DB
        $post['waktu_awal'] = json_encode($post['waktu_awal']);
        $post['waktu_akhir'] = json_encode($post['waktu_akhir']);
        $post['kegiatanx'] = json_encode($post['kegiatanx']);
        $post['lokasix'] = json_encode($post['lokasix']);
        $post['entrian'] = implode("\n", $uploaded_urls);
        $this->db->insert('lpd', $post);

        // Generate file Word
        $template = new \PhpOffice\PhpWord\TemplateProcessor(APPPATH . 'templates/templateLPD_mitra.docx');
        $template->setValue('nama_peg', $post['nama']);
        $template->setValue('nip', $post['nip']);
        $template->setValue('jabatan_peg', $post['jabatan']);
        $template->setValue('nomorST_peg', $post['no_st']);
        $template->setValue('tanggalST_peg', $this->format_tanggal_indo($post['tgl_st']));
        $template->setValue('tanggaltugas_peg', $this->format_tanggal_indo($post['tgl_tugas']));
        $template->setValue('tujuantugas_peg', $post['tujuan_tugas']);
        $template->setValue('resume_peg', $post['resume']);
        $template->setValue('tanggal_buat', $this->format_tanggal_indo($post['tanggal_buat']));
        $template->cloneRowAndSetValues('no', $rows);

        // Tambah dokumentasi foto ke file Word
        for ($i = 0; $i < 3; $i++) {
            $tag = 'foto' . ($i + 1);
            if (isset($uploaded_paths[$i]) && file_exists($uploaded_paths[$i])) {
                $template->setImageValue($tag, [
                    'path' => $uploaded_paths[$i],
                    'width' => 300,
                    'height' => 200
                ]);
            } else {
                $template->setValue($tag, '-');
            }
        }

        $template->saveAs($savePath);

        // Set flashdata untuk auto-download dan notifikasi
        $this->session->set_flashdata('file_generated', $filename);
        $this->session->set_flashdata('success', 'LPD Mitra berhasil disimpan dan file Word berhasil dibuat.');

        // Redirect ke halaman LPD
        redirect(base_url('persuratan/lpd'));
    }

    private function format_nip($nip_raw)
    {
        if (strlen($nip_raw) === 18) {
            return substr($nip_raw, 0, 8) . ' ' .
                substr($nip_raw, 8, 6) . ' ' .
                substr($nip_raw, 14, 1) . ' ' .
                substr($nip_raw, 15, 3);
        }
        return $nip_raw;
    }

    private function convert_day($day)
    {
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        return $days[$day] ?? $day;
    }

    private function format_tanggal_indo($date)
    {
        $bulan = [
            1 => 'Januari',
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

        $tanggal = date('j', strtotime($date));
        $bulanIndo = $bulan[(int) date('m', strtotime($date))];
        $tahun = date('Y', strtotime($date));

        return $tanggal . ' ' . $bulanIndo . ' ' . $tahun;
    }

    private function generate_resume($nama, $tgl_tugas, $lokasix, $kegiatanx)
    {
        // Format hari dan tanggal dalam bahasa Indonesia
        $timestamp = strtotime($tgl_tugas);
        $hari = $this->convert_day(date('l', $timestamp)); // pastikan fungsi ini ada
        $tanggal = date('j F Y', $timestamp);
        $haritanggal = "$hari, $tanggal";

        // Ambil lokasi dan kegiatan unik
        $lokasi_unik = array_unique(array_filter($lokasix));
        $kegiatan_unik = array_unique(array_filter($kegiatanx));

        // Buat string lokasi dan kegiatan
        $lokasi_str = implode(', ', $lokasi_unik);
        $kegiatan_str = implode('; ', $kegiatan_unik);

        // Paragraf 1 – pembukaan
        $paragraf1 = "Pada hari $haritanggal, telah dilaksanakan perjalanan dinas oleh petugas atas nama $nama ke lokasi $lokasi_str. Tujuan perjalanan ini adalah untuk menjalankan tugas yang telah ditetapkan dalam surat tugas resmi.";

        // Paragraf 2 – deskripsi kegiatan
        $paragraf2 = "Selama kegiatan berlangsung, petugas melaksanakan aktivitas seperti $kegiatan_str. Kegiatan-kegiatan tersebut dilakukan berdasarkan jadwal dan rundown yang telah direncanakan sebelumnya di berbagai titik lokasi.";

        // Paragraf 3 – kesimpulan
        $paragraf3 = "Secara umum, kegiatan dapat berjalan dengan lancar dan sesuai dengan rencana. Jika terdapat kendala teknis di lapangan, petugas telah melakukan koordinasi awal untuk penyelesaian lebih lanjut.";

        // Gabung semua paragraf
        return $paragraf1 . "\n\n" . $paragraf2 . "\n\n" . $paragraf3;
    }

    public function import_lpd_bulk()
    {
        $file = $_FILES['file_excel']['tmp_name'];
        if (!$file) {
            $this->session->set_flashdata('error', 'File tidak ditemukan.');
            redirect('Persuratan');
        }

        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $outputDir = FCPATH . 'uploads/laporan/';
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $fileList = [];

        for ($i = 2; $i <= count($sheet); $i++) {
            $row = $sheet[$i];

            $nama = trim($row['A']);
            $nip = trim($row['B']);
            $jabatan = trim($row['C']);
            $no_st = trim($row['D']);
            $tgl_st = date('Y-m-d', strtotime($row['E']));
            $tgl_tugas = date('Y-m-d', strtotime($row['F']));
            $tujuan = trim($row['G']);
            $resume = !empty($row['H']) ? $row['H'] : $this->_generate_resume($nama, $tgl_tugas, $tujuan);
            $tgl_buat = date('Y-m-d', strtotime($row['I']));
            $jenis = isset($row['J']) ? strtolower(trim($row['J'])) : 'pegawai';

            // Rundown
            $waktu_awal = isset($row['K']) ? explode(',', str_replace(['[', ']', '"'], '', $row['K'])) : [];
            $waktu_akhir = isset($row['L']) ? explode(',', str_replace(['[', ']', '"'], '', $row['L'])) : [];
            $kegiatanx = isset($row['M']) ? explode(',', str_replace(['[', ']', '"'], '', $row['M'])) : [];
            $lokasix = isset($row['N']) ? explode(',', str_replace(['[', ']', '"'], '', $row['N'])) : [];

            // Pilih template berdasarkan jenis
            if ($jenis == 'pegawai') {
                $template_path = APPPATH . 'templates/templateLPD_organik.docx';
            } elseif ($jenis == 'mitra') {
                $template_path = APPPATH . 'templates/templateLPD_mitra.docx';
            } else {
                continue;
            }

            // Format nama file: Nama_TanggalTugas.docx
            $tgl_label = date('Ymd', strtotime($tgl_tugas));
            $nama_file_base = preg_replace('/[^a-zA-Z0-9_]/', '_', $nama) . '_' . $tgl_label;
            $nama_file_final = $nama_file_base . '.docx';
            $filepath = $outputDir . $nama_file_final;

            // Cek apakah file dengan nama sama sudah ada, jika ya tambahkan (1), (2), dst
            $counter = 1;
            while (file_exists($filepath)) {
                $nama_file_final = $nama_file_base . "($counter).docx";
                $filepath = $outputDir . $nama_file_final;
                $counter++;
            }

            // Simpan ke DB
            $data = [
                'nama' => $nama,
                'nip' => $nip,
                'jabatan' => $jabatan ?: ($jenis == 'mitra' ? 'Mitra Statistik' : ''),
                'no_st' => $no_st,
                'tgl_st' => $tgl_st,
                'tgl_tugas' => $tgl_tugas,
                'tujuan_tugas' => $tujuan,
                'resume' => $resume,
                'tanggal_buat' => $tgl_buat,
                'jenis' => $jenis,
                'entrian' => '',
                'waktu_awal' => json_encode($waktu_awal),
                'waktu_akhir' => json_encode($waktu_akhir),
                'kegiatanx' => json_encode($kegiatanx),
                'lokasix' => json_encode($lokasix),
                'nama_file_word' => $nama_file_final
            ];
            $this->db->insert('lpd', $data);

            // Generate dokumen
            $this->_generate_word($data, $filepath, $template_path);
            if (file_exists($filepath)) {
                $fileList[] = $filepath;
            }
        }

        // ZIP hasil
        $zipDir = FCPATH . 'uploads/lpd_bulk/';
        if (!is_dir($zipDir)) {
            mkdir($zipDir, 0777, true);
        }

        $zipFilename = 'lpdbulk_' . date('Ymd_His') . '.zip';
        $zipPath = $zipDir . $zipFilename;

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($fileList as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
        }

        redirect(base_url('uploads/lpd_bulk/' . $zipFilename));
    }

    private function _generate_resume($nama, $tgl, $tujuan)
    {
        $hari = $this->convert_day(date('l', strtotime($tgl)));
        $tanggal = $this->format_tanggal_indonesia($tgl);

        return "Pada hari $hari, $tanggal telah dilakukan perjalanan dinas oleh petugas atas nama $nama dengan tujuan $tujuan. 
Kegiatan dilakukan sesuai rencana dan berjalan lancar tanpa kendala berarti. 
Apabila ditemukan permasalahan teknis di lapangan, langsung dilakukan koordinasi untuk penyelesaian.";
    }

    private function _generate_word($data, $filepath, $template_path)
    {
        $template = new \PhpOffice\PhpWord\TemplateProcessor($template_path);

        $tanggal_tugas = $this->format_tanggal_indonesia($data['tgl_tugas']);
        $tanggal_st = $this->format_tanggal_indonesia($data['tgl_st']);
        $tanggal_buat = $this->format_tanggal_indonesia($data['tanggal_buat']);

        // Ambil Hari & Tanggal
        $hari = $this->convert_day(date('l', strtotime($data['tgl_tugas'])));
        $tanggal = date('j F Y', strtotime($data['tgl_tugas']));
        $haritanggal = "$hari, $tanggal";

        // Rundown
        $waktu_awal = json_decode($data['waktu_awal'], true);
        $waktu_akhir = json_decode($data['waktu_akhir'], true);
        $kegiatanx = json_decode($data['kegiatanx'], true);
        $lokasix = json_decode($data['lokasix'], true);

        $rows = [];
        for ($i = 0; $i < count($waktu_awal); $i++) {
            $rows[] = [
                'no' => $i + 1,
                'haritanggal' => ($i == 0) ? $tanggal_tugas : '',
                'waktu' => $waktu_awal[$i] . ' – ' . $waktu_akhir[$i],
                'kegiatan' => $kegiatanx[$i],
                'lokasi' => $lokasix[$i]
            ];
        }

        // Isi Template
        $template->setValue('nama_peg', $data['nama']);
        $template->setValue('nip', $data['nip']);
        $template->setValue('jabatan_peg', $data['jabatan']);
        $template->setValue('nomorST_peg', $data['no_st']);
        $template->setValue('tanggalST_peg', $tanggal_st);
        $template->setValue('tanggaltugas_peg', $tanggal_tugas);
        $template->setValue('tujuantugas_peg', $data['tujuan_tugas']);
        $template->setValue('resume_peg', $data['resume']);
        $template->setValue('tanggal_buat', $tanggal_buat);

        // Rundown ke template
        if (!empty($rows)) {
            $template->cloneRowAndSetValues('no', $rows);
        }

        // Simpan file
        $template->saveAs($filepath);
    }

    private function _sanitize_filename($name)
    {
        return preg_replace('/[^A-Za-z0-9_\-]/', '_', str_replace(' ', '_', $name));
    }


    private function tanggal_indo($tgl)
    {
        $bulan = [
            '',
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
        $timestamp = strtotime($tgl);
        $hari = $this->convert_day(date('l', $timestamp));
        return $hari . ', ' . date('j', $timestamp) . ' ' . $bulan[date('n', $timestamp)] . ' ' . date('Y', $timestamp);
    }

    private function format_tanggal_indonesia($tanggal)
    {
        $bulan = [
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

        $tanggal = date('Y-m-d', strtotime($tanggal));
        $parts = explode('-', $tanggal);
        $tgl = (int) $parts[2];
        $bln = (int) $parts[1];
        $thn = $parts[0];

        return $tgl . ' ' . $bulan[$bln] . ' ' . $thn;
    }
}
