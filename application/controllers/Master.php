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

    /*
    public function update_menu_structure()
    {
        // 1. Rename existing "Data Mitra" (ID 4) to "Mitra 2025" (Current default)
        // Or better yet, delete and re-insert to keep clean ids?
        // Let's repurpose ID 4 for 2025

        $this->db->where('id', 4);
        $this->db->update('user_sub_menu', [
            'title' => 'Mitra 2025',
            'url' => 'master/mitra/2025',
            'icon' => 'fas fa-fw fa-users'
        ]);

        // 2. Insert 2024 and 2026
        // Check if 2024 exists
        $cek24 = $this->db->get_where('user_sub_menu', ['title' => 'Mitra 2024'])->num_rows();
        if ($cek24 == 0) {
            $this->db->insert('user_sub_menu', [
                'menu_id' => 1, // Master
                'title' => 'Mitra 2024',
                'url' => 'master/mitra/2024',
                'icon' => 'fas fa-fw fa-history',
                'is_active' => 1
            ]);
        }

        // Check if 2026 exists
        $cek26 = $this->db->get_where('user_sub_menu', ['title' => 'Mitra 2026'])->num_rows();
        if ($cek26 == 0) {
            $this->db->insert('user_sub_menu', [
                'menu_id' => 1, // Master
                'title' => 'Mitra 2026',
                'url' => 'master/mitra/2026',
                'icon' => 'fas fa-fw fa-user-plus',
                'is_active' => 1
            ]);
        }

        echo "Menu Updated!";
        die;
    }
    */

    /*
    public function update_icons_and_order()
    {
        // 1. Update Icons for beautiful look
        $icons = [
            'Admin' => 'fas fa-fw fa-tachometer-alt',
            'User' => 'fas fa-fw fa-user-circle',
            'Menu' => 'fas fa-fw fa-folder-open',
            'Utility' => 'fas fa-fw fa-tools' // Assuming Utility exists
        ];

        foreach ($icons as $menu => $icon) {
            $this->db->where('menu', $menu);
            $this->db->update('user_menu', ['icon' => $icon]); // Assuming 'icon' column exists? 
            // Wait, sidebar.php logic:
            // The ICON is in `user_sub_menu`. 
            // The `user_menu` usually doesn't have an icon displayed in the loop shown in sidebar.php line 40:
            // <i class="fas fa-fw fa-folder text-primary"></i>  <-- HARDCODED FOLDER
        }

        // Correcting: The sidebar code HARDCODES the folder icon for top-level menus!
        // Line 40: <i class="fas fa-fw fa-folder text-primary"></i>
        // I need to change sidebar.php to use a dynamic icon if I want "bermacam macam".
        // But first, let's update sub-menu icons for User/Edit etc.

        $sub_icons = [
            'My Profile' => 'fas fa-fw fa-id-card',
            'Edit Profile' => 'fas fa-fw fa-user-edit',
            'Change Password' => 'fas fa-fw fa-key',
            'Menu Management' => 'fas fa-fw fa-bars',
            'Submenu Management' => 'fas fa-fw fa-list-alt',
            // Master/Mitra already handled
        ];

        foreach ($sub_icons as $title => $icon) {
            $this->db->where('title', $title);
            $this->db->update('user_sub_menu', ['icon' => $icon]);
        }

        // Reordering: Admin is likely ID 1. User ID 2.
        // If they are correct, just ensure sidebar defaults to Admin on top.
        // Sidebar query: ORDER BY `user_access_menu`.`menu_id` ASC
        // If Admin is ID 1, it shows first.

        echo "Icons Updated. Please check sidebar.php for top-level icon logic.";
        die;
    }
    */

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
            exit; // Force stop execution to prevent extra output
        } else {
            show_404();
        }
    }

    public function mitra($tahun = null)
    {
        // Enforce year parameter: Redirect to current year if missing
        if (!$tahun) {
            redirect('master/mitra/' . date('Y'));
        }

        // Keep legacy AJAX support just in case, but preferred new endpoint
        if ($this->input->is_ajax_request() && $this->input->post('kode_kec')) {
            $kode_kec = $this->input->post('kode_kec');
            $this->db->like('kode', $kode_kec, 'after');
            $desa = $this->db->get('kode_keldes')->result_array();
            echo json_encode($desa);
            return;
        }

        $data['title'] = 'Data Mitra';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['tahun'] = $tahun; // Pass selected year to view

        $this->db->select('mitra.*, mitra_tahun.posisi, mitra_tahun.is_active, kode_kecamatan.nama as nama_kecamatan');
        $this->db->from('mitra');
        $this->db->join('mitra_tahun', 'mitra.id_mitra = mitra_tahun.id_mitra');
        $this->db->join('kode_kecamatan', 'mitra.kecamatan = kode_kecamatan.kode', 'left'); // LEFT JOIN to show all mitra
        $this->db->where('mitra_tahun.tahun', $tahun); // Filter by year in the join table
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
                'email' => $this->input->post('email'),
                'kecamatan' => $this->input->post('kecamatan'),
                'desa' => substr($this->input->post('desa'), 3),
                'alamat' => $this->input->post('alamat'),
                'jk' => $this->input->post('jk'),
                'no_hp' => $this->input->post('no_hp'),
                'sobat_id' => $this->input->post('sobat_id')
            ];

            $tahun_save = $this->input->post('tahun_input') ? $this->input->post('tahun_input') : $tahun;

            // Check if profile exists (by NIK)
            $this->db->where('nik', $data['nik']);
            $existing = $this->db->get('mitra')->row_array();

            if ($existing) {
                $id_mitra = $existing['id_mitra'];
                $this->db->where('id_mitra', $id_mitra);
                $this->db->update('mitra', $data);
            } else {
                $this->db->insert('mitra', $data);
                $id_mitra = $this->db->insert_id();
            }

            // Check if yearly status exists
            $this->db->where('id_mitra', $id_mitra);
            $this->db->where('tahun', $tahun_save);
            if ($this->db->get('mitra_tahun')->num_rows() == 0) {
                $this->db->insert('mitra_tahun', [
                    'id_mitra' => $id_mitra,
                    'tahun' => $tahun_save,
                    'posisi' => $this->input->post('posisi'),
                    'is_active' => 1
                ]);
            }

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Mitra saved for ' . $tahun_save . '!</div>');
            redirect('master/mitra/' . $tahun_save);
        }
    }


    public function import($tahun = null)
    {
        // Validate year parameter
        if (!$tahun) {
            $tahun = date('Y'); // Default to current year
        }

        // Validate year is within allowed range
        if (!in_array($tahun, [2024, 2025, 2026])) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Tahun tidak valid! Hanya 2024, 2025, atau 2026 yang diperbolehkan.</div>');
            redirect('master/mitra');
        }

        $this->form_validation->set_rules('excel', 'File', 'trim|required');

        if ($_FILES['excel']['name'] == '') {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">File tidak ditemukan!</div>');
            redirect('master/mitra/' . $tahun);
        }

        $config['upload_path'] = FCPATH . 'assets/excel/';
        $config['allowed_types'] = 'xls|xlsx';

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('excel')) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal upload: ' . $this->upload->display_errors() . '</div>');
            redirect('master/mitra/' . $tahun);
        }

        $data = $this->upload->data();
        $inputFileName = './assets/excel/' . $data['file_name'];

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();

            // Use rangeToArray to ensure we get all data up to the highest row
            $sheetData = $worksheet->rangeToArray(
                'A1:' . $highestColumn . $highestRow,
                null,
                true,
                true,
                true
            );
        } catch (Exception $e) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Error loading file: ' . $e->getMessage() . '</div>');
            redirect('master/mitra/' . $tahun);
        }

        $index = 0;
        $resultData = [];

        foreach ($sheetData as $key => $value) {
            if ($key != 1) {
                // Skip row if BOTH NIK (A) and Nama (B) are empty
                if (empty($value['A']) && empty($value['B'])) {
                    continue;
                }

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
            $index++;
        }

        unlink('./assets/excel/' . $data['file_name']);

        if (count($resultData) > 0) {
            // Pass the year parameter to insert_batch
            $this->Master_model->insert_batch($resultData, $tahun);

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Import data mitra berhasil untuk tahun ' . $tahun . '! Total: ' . count($resultData) . ' mitra.</div>');
            redirect('master/mitra/' . $tahun);
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Tidak ada data yang diimport atau file kosong.</div>');
            redirect('master/mitra/' . $tahun);
        }
    }


    public function import_pegawai()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);

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

        // Ambil Filter
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        $data['filter_bulan'] = $bulan;
        $data['filter_tahun'] = $tahun;

        // Fetch Kriteria
        $kriteria = $this->db->get('kriteria')->result_array();
        $data['kriteria_list'] = $kriteria;

        // Base Query Logic
        $where_pencacah = "WHERE akp.id_mitra = ?";
        $where_pengawas = "WHERE akp.id_pengawas = ?";
        $params_pencacah = [$id_mitra];
        $params_pengawas = [$id_mitra];

        if ($bulan) {
            $where_pencacah .= " AND MONTH(FROM_UNIXTIME(k.finish)) = ?";
            $where_pengawas .= " AND MONTH(FROM_UNIXTIME(k.finish)) = ?";
            $params_pencacah[] = $bulan;
            $params_pengawas[] = $bulan;
        }
        if ($tahun) {
            $where_pencacah .= " AND YEAR(FROM_UNIXTIME(k.finish)) = ?";
            $where_pengawas .= " AND YEAR(FROM_UNIXTIME(k.finish)) = ?";
            $params_pencacah[] = $tahun;
            $params_pengawas[] = $tahun;
        }

        // Fetch Pencacah Activities
        $sql_pencacah = "
            SELECT akp.id as id_plot, k.nama as nama_kegiatan, k.start, k.finish, AVG(ap.nilai) as nilai_rata_rata
            FROM all_kegiatan_pencacah akp
            JOIN kegiatan k ON akp.kegiatan_id = k.id
            LEFT JOIN all_penilaian ap ON akp.id = ap.all_kegiatan_pencacah_id
            $where_pencacah
            GROUP BY akp.id
            ORDER BY k.finish DESC
        ";
        $kegiatan_pencacah = $this->db->query($sql_pencacah, $params_pencacah)->result_array();

        // Attach detailed scores for Pencacah
        foreach ($kegiatan_pencacah as &$kp) {
            $kp['details'] = [];
            $scores = $this->db->select('k.nama as kriteria, ap.nilai')
                ->from('all_penilaian ap')
                ->join('kriteria k', 'ap.kriteria_id = k.id')
                ->where('ap.all_kegiatan_pencacah_id', $kp['id_plot']) // Use unique ID from all_kegiatan_pencacah
                ->get()->result_array();
            $kp['details'] = $scores;
        }
        $data['kegiatan_pencacah'] = $kegiatan_pencacah;


        // Fetch Pengawas Activities
        $sql_pengawas = "
            SELECT akp.id as id_plot, k.nama as nama_kegiatan, k.start, k.finish, AVG(ap.nilai) as nilai_rata_rata
            FROM all_kegiatan_pengawas akp
            JOIN kegiatan k ON akp.kegiatan_id = k.id
            LEFT JOIN all_penilaian_pengawas ap ON akp.id = ap.all_kegiatan_pengawas_id
            $where_pengawas
            GROUP BY akp.id
            ORDER BY k.finish DESC
        ";
        $kegiatan_pengawas = $this->db->query($sql_pengawas, $params_pengawas)->result_array();

        // Attach detailed scores for Pengawas
        foreach ($kegiatan_pengawas as &$kp) {
            $kp['details'] = [];
            $scores = $this->db->select('k.nama as kriteria, ap.nilai')
                ->from('all_penilaian_pengawas ap')
                ->join('kriteria k', 'ap.kriteria_id = k.id')
                ->where('ap.all_kegiatan_pengawas_id', $kp['id_plot'])
                ->get()->result_array();
            $kp['details'] = $scores;
        }
        $data['kegiatan_pengawas'] = $kegiatan_pengawas;

        // Calculate Overall Average
        $total_nilai = 0;
        $count = 0;
        foreach ($data['kegiatan_pencacah'] as $kp) {
            if ($kp['nilai_rata_rata'] > 0) {
                $total_nilai += $kp['nilai_rata_rata'];
                $count++;
            }
        }
        foreach ($data['kegiatan_pengawas'] as $kp) {
            if ($kp['nilai_rata_rata'] > 0) {
                $total_nilai += $kp['nilai_rata_rata'];
                $count++;
            }
        }
        $data['average_all'] = $count > 0 ? round($total_nilai / $count, 2) : 0;

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
                'email' => $this->input->post('email'),
                'kecamatan' => $this->input->post('kecamatan'),
                'desa' => (strlen($this->input->post('desa')) > 3) ? substr($this->input->post('desa'), -3) : $this->input->post('desa'),
                'alamat' => $this->input->post('alamat'),
                'jk' => $this->input->post('jk'),
                'no_hp' => $this->input->post('no_hp'),
                'sobat_id' => $this->input->post('sobat_id')
            ];

            $this->db->where('id_mitra', $id_mitra);
            $this->db->update('mitra', $data);

            // Fetch year to redirect back correctly
            $mitra_tahun = $this->db->get_where('mitra_tahun', ['id_mitra' => $id_mitra])->row_array();
            $tahun_redir = $mitra_tahun['tahun'] ?? '';

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Mitra profile has been updated!</div>');
            redirect('master/mitra/' . $tahun_redir);
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

    function deletemitra($id_mitra, $tahun = null)
    {
        if ($tahun) {
            // Precise deletion if year is known
            // Note: If you want to delete ONLY the relationship for that year:
            $this->db->where('id_mitra', $id_mitra);
            $this->db->where('tahun', $tahun);
            $this->db->delete('mitra_tahun');

            // Check if mitra has other years. If not, maybe delete master record?
            // For now, let's just delete the year record as per "delete from list" 
        } else {
            // Legal fallback (dangerous if multiple years)
            $this->Master_model->deletemitra($id_mitra);
        }

        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Mitra has been deleted from ' . $tahun . '!</div>');
        redirect('master/mitra/' . $tahun);
    }

    public function deactivated($id_mitra, $tahun = null)
    {
        if (!$tahun) {
            // Fallback: try to guess (legacy)
            $mitra = $this->db->get_where('mitra_tahun', ['id_mitra' => $id_mitra])->row_array();
            $tahun = $mitra['tahun'] ?? date('Y');
        }

        $this->Master_model->deactivated($id_mitra, $tahun);
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Mitra has been deactivated for ' . $tahun . '!</div>');
        redirect('master/mitra/' . $tahun);
    }

    public function activated($id_mitra, $tahun = null)
    {
        if (!$tahun) {
            // Fallback
            $mitra = $this->db->get_where('mitra_tahun', ['id_mitra' => $id_mitra])->row_array();
            $tahun = $mitra['tahun'] ?? date('Y');
        }

        $this->Master_model->activated($id_mitra, $tahun);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Mitra has been activated for ' . $tahun . '!</div>');
        redirect('master/mitra/' . $tahun);
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

    public function download_format()
    {
        $this->load->helper('download');
        // Check both locations just in case, preferring assets/excel
        $path = './assets/excel/data_mitra.xlsx';
        if (file_exists($path)) {
            $data = file_get_contents($path);
            force_download("format_mitra.xlsx", $data);
        } else {
            echo "File not found at $path";
        }
    }
    public function export_mitra($tahun = null)
    {
        if (!$tahun)
            $tahun = date('Y');

        $this->db->select('mitra.*, mitra_tahun.posisi');
        $this->db->from('mitra');
        $this->db->join('mitra_tahun', 'mitra.id_mitra = mitra_tahun.id_mitra');
        $this->db->where('mitra_tahun.tahun', $tahun);
        $mitra_data = $this->db->get()->result_array();

        // Load PHPExcel Library
        require_once FCPATH . 'assets/phpexcel/Classes/PHPExcel.php';

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("BPS")
            ->setLastModifiedBy("BPS")
            ->setTitle("Data Mitra " . $tahun)
            ->setSubject("Data Mitra " . $tahun)
            ->setDescription("Data Mitra Export");

        $sheet = $objPHPExcel->setActiveSheetIndex(0);

        // Set Headers match Import Template
        $headers = ['NIK', 'Nama', 'Posisi', 'Email', 'Kecamatan', 'Desa', 'Alamat', 'JK', 'No HP', 'Sobat ID'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        // Style Header
        $headerStyle = array(
            'font' => array('bold' => true),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'FFFF00')
            )
        );
        $sheet->getStyle('A1:J1')->applyFromArray($headerStyle);

        $row = 2;
        foreach ($mitra_data as $m) {
            $sheet->setCellValueExplicit('A' . $row, $m['nik'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $row, $m['nama']);
            $sheet->setCellValue('C' . $row, $m['posisi']);
            $sheet->setCellValue('D' . $row, $m['email']);
            $sheet->setCellValue('E' . $row, $m['kecamatan']);
            $sheet->setCellValue('F' . $row, $m['desa']);
            $sheet->setCellValue('G' . $row, $m['alamat']);
            $sheet->setCellValue('H' . $row, $m['jk']);
            $sheet->setCellValueExplicit('I' . $row, $m['no_hp'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValue('J' . $row, $m['sobat_id']);
            $row++;
        }

        $filename = 'Data_Mitra_' . $tahun . '_' . date('YmdHis') . '.xlsx';

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
}
