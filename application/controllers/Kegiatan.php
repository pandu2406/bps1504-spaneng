<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kegiatan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in_user();
        $this->load->model('Kegiatan_model');
        $this->load->model('Rekap_model');
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


    public function clear_session()
    {
        $this->session->unset_userdata('message');
        echo "<script>alert('Session flashdata cleared!'); window.close();</script>";
        redirect('kegiatan/survei');
    }

    public function survei()
    {
        $data['title'] = 'Survei';
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email')
        ])->row_array();

        // Get Filter Parameters
        $seksi_id = $this->input->get('seksi_id');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $year = $this->input->get('year');

        // Dynamic Filtering Query
        $this->db->select('kegiatan.*, seksi.nama AS nama_seksi');
        $this->db->from('kegiatan');
        $this->db->join('seksi', 'kegiatan.seksi_id = seksi.id', 'left');
        $this->db->where('kegiatan.jenis_kegiatan', 1);

        if ($seksi_id) {
            $this->db->where('kegiatan.seksi_id', $seksi_id);
        }
        if ($start_date) {
            $this->db->where('kegiatan.start >=', strtotime($start_date));
        }
        if ($end_date) {
            $this->db->where('kegiatan.finish <=', strtotime($end_date));
        }
        if ($year) {
            $this->db->where("FROM_UNIXTIME(kegiatan.start, '%Y') =", $year);
        }

        $this->db->order_by('kegiatan.finish', 'DESC');
        $data['survei'] = $this->db->get()->result_array();

        $data['seksi'] = $this->db->get('seksi')->result_array();
        $data['posisi'] = $this->db->get('posisi')->result_array();
        $data['satuan'] = $this->db->get('satuan')->result_array();
        $data['sistempembayaran_list'] = $this->db->get('sistempembayaran')->result_array();
        // --- MASTER DATA POPULATION START ---
        // 1. Get existing Master Data
        $master_kegiatan = $this->db->get_where('master_kegiatan', ['jenis_kegiatan' => 1])->result_array();

        // 2. Get Distinct Names from History (kegiatan) - NOT kegiatan_old
        $history_kegiatan = $this->db->select('DISTINCT(nama) as nama')->get('kegiatan')->result_array();

        // 3. Get Classification Rules
        $period_rules = $this->db->get('master_periodisitas')->result_array();

        // 3. Merge and Classify
        $existing_names = array_column($master_kegiatan, 'nama');

        foreach ($history_kegiatan as $h) {
            $nama = $h['nama'];
            if (!in_array($nama, $existing_names)) {
                // Classify based on Keywords from Database
                $periodisitas = 'Tahunan'; // Default

                foreach ($period_rules as $rule) {
                    if (!empty($rule['kata_kunci'])) {
                        $keywords = explode(',', $rule['kata_kunci']);
                        foreach ($keywords as $keyword) {
                            if (stripos($nama, trim($keyword)) !== false) {
                                $periodisitas = $rule['nama'];
                                break 2; // Break both loops if match found
                            }
                        }
                    }
                }

                $master_kegiatan[] = [
                    'nama' => $nama,
                    'periodisitas' => $periodisitas,
                    'jenis_kegiatan' => 1
                ];

                $existing_names[] = $nama;
            }
        }

        // Sort alphabetically
        usort($master_kegiatan, function ($a, $b) {
            return strcmp($a['nama'], $b['nama']);
        });

        $data['master_kegiatan'] = $master_kegiatan;
        // --- MASTER DATA POPULATION END ---

        // Pass filter values back to view
        $data['filter_seksi'] = $seksi_id;
        $data['filter_start'] = $start_date;
        $data['filter_end'] = $end_date;
        $data['filter_year'] = $year;

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
        $this->form_validation->set_rules('periodisitas', 'Periodisitas', 'required|trim');

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
                    'periodisitas' => $this->input->post('periodisitas', true)
                ];

                $this->db->insert('kegiatan', $insert);
                $kegiatan_id_inserted = $this->db->insert_id();

                // Sync with master_kegiatan
                $nama_kegiatan = $this->input->post('nama', true);
                $cek_master = $this->db->get_where('master_kegiatan', ['nama' => $nama_kegiatan])->row_array();
                if (!$cek_master) {
                    $this->db->insert('master_kegiatan', [
                        'nama' => $nama_kegiatan,
                        'periodisitas' => $this->input->post('periodisitas', true),
                        'jenis_kegiatan' => 1
                    ]);
                }
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

        // Get Filter Parameters
        $seksi_id = $this->input->get('seksi_id');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $year = $this->input->get('year');

        // Dynamic Filtering Query
        $this->db->select('kegiatan.*, seksi.nama AS nama_seksi');
        $this->db->from('kegiatan');
        $this->db->join('seksi', 'kegiatan.seksi_id = seksi.id', 'left');
        $this->db->where('kegiatan.jenis_kegiatan', 2);

        if ($seksi_id) {
            $this->db->where('kegiatan.seksi_id', $seksi_id);
        }
        if ($start_date) {
            $this->db->where('kegiatan.start >=', strtotime($start_date));
        }
        if ($end_date) {
            $this->db->where('kegiatan.finish <=', strtotime($end_date));
        }
        if ($year) {
            $this->db->where("FROM_UNIXTIME(kegiatan.start, '%Y') =", $year);
        }

        $this->db->order_by('kegiatan.finish', 'DESC');
        $data['sensus'] = $this->db->get()->result_array();

        $data['seksi'] = $this->db->get('seksi')->result_array();
        $data['posisi'] = $this->db->get('posisi')->result_array();
        $data['satuan'] = $this->db->get('satuan')->result_array();
        $data['sistempembayaran_list'] = $this->db->get('sistempembayaran')->result_array();
        $data['master_kegiatan'] = $this->db->get_where('master_kegiatan', ['jenis_kegiatan' => 2])->result_array();

        // Pass filter values back to view
        $data['filter_seksi'] = $seksi_id;
        $data['filter_start'] = $start_date;
        $data['filter_end'] = $end_date;
        $data['filter_year'] = $year;

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
        $this->form_validation->set_rules('periodisitas', 'Periodisitas', 'required|trim');

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
                    'periodisitas' => $this->input->post('periodisitas', true)
                ];

                $this->db->insert('kegiatan', $insert);
                $kegiatan_id_inserted = $this->db->insert_id();

                // Sync with master_kegiatan
                $nama_kegiatan = $this->input->post('nama', true);
                $cek_master = $this->db->get_where('master_kegiatan', ['nama' => $nama_kegiatan])->row_array();
                if (!$cek_master) {
                    $this->db->insert('master_kegiatan', [
                        'nama' => $nama_kegiatan,
                        'periodisitas' => $this->input->post('periodisitas', true),
                        'jenis_kegiatan' => 2
                    ]);
                }
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
        $this->form_validation->set_rules('periodisitas', 'Periodisitas', 'required|trim');
        $this->form_validation->set_rules('beban_standar', 'Beban Standar', 'required|trim|integer');

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
                    'beban_standar' => $this->input->post('beban_standar', true),
                    'ob' => $this->input->post('ob', true)
                ];

                // Reset notification flag if finish date is extended to future
                $debug_log = "DEBUG EXECUTED AT " . date('Y-m-d H:i:s') . "\n";
                $debug_log .= "Finish Date: " . $finish . "\n";
                $debug_log .= "Current Time: " . time() . "\n";
                file_put_contents('application/logs/custom_debug.txt', $debug_log, FILE_APPEND);

                if ($finish > time()) {
                    $update['is_notification_sent'] = 0;
                }

                $this->db->where('id', $id);
                $this->db->update('kegiatan', $update);

                // Check for completion and notify immediately
                if ($finish < time()) {
                    log_message('error', 'DEBUG: Finish < Time. Checking if notification sent...');
                    // Check if already sent
                    $kegiatan = $this->db->get_where('kegiatan', ['id' => $id])->row_array();
                    log_message('error', 'DEBUG: is_notification_sent status: ' . $kegiatan['is_notification_sent']);

                    if ($kegiatan && $kegiatan['is_notification_sent'] == 0) {
                        log_message('error', 'DEBUG: Sending notifications...');
                        // Send notifications
                        $this->db->distinct();
                        $this->db->select('id_pengawas');
                        $this->db->where('kegiatan_id', $id);
                        $this->db->where('id_pengawas !=', 0);
                        $assignments = $this->db->get('all_kegiatan_pencacah')->result_array();

                        if (empty($assignments)) {
                            log_message('error', 'DEBUG: No assignments found.');
                        }

                        foreach ($assignments as $assign) {
                            $id_pengawas = $assign['id_pengawas'];
                            $pengawas = $this->db->get_where('pegawai', ['id_peg' => $id_pengawas])->row_array();
                            if (!$pengawas) {
                                $pengawas = $this->db->get_where('mitra', ['id_mitra' => $id_pengawas])->row_array();
                            }

                            if ($pengawas && !empty($pengawas['email'])) {
                                log_message('error', 'DEBUG: Sending email to ' . $pengawas['email']);
                                $subject = "Pemberitahuan Kegiatan Selesai: " . $kegiatan['nama'];
                                $message = "
                                    <h3>Halo {$pengawas['nama']},</h3>
                                    <p>Kegiatan <b>{$kegiatan['nama']}</b> telah berakhir (Status Diperbarui).</p>
                                    <p>Mohon segera lakukan penilaian kinerja terhadap Pencacah yang Anda awasi melalui aplikasi SPANENG.</p>
                                    <br>
                                    <p>Terima kasih,<br>Admin SPANENG</p>
                                ";
                                $result = $this->_send_notification($pengawas['email'], $subject, $message);
                                log_message('error', 'DEBUG: Email send result: ' . ($result ? 'TRUE' : 'FALSE'));
                            } else {
                                log_message('error', 'DEBUG: Pengawas not found or no email for ID ' . $id_pengawas);
                            }
                        }
                        // Mark as sent
                        $this->db->where('id', $id);
                        $this->db->update('kegiatan', ['is_notification_sent' => 1]);
                    } else {
                        log_message('error', 'DEBUG: Already sent or kegiatan not found.');
                    }
                } else {
                    log_message('error', 'DEBUG: Finish date is in future. No notification needed.');
                }

                // Update juga ke Rincian Kegiatan untuk semua pencacah yang terlibat (Merata)
                $beban_standar = $this->input->post('beban_standar', true);
                $honor_satuan = $this->input->post('honor', true);
                $total_honor = $beban_standar * $honor_satuan;

                $update_rincian = [
                    'beban' => $beban_standar,
                    'honor' => $honor_satuan,
                    'total_honor' => $total_honor
                ];
                $this->db->where('kegiatan_id', $id);
                $this->db->update('rinciankegiatan', $update_rincian);

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
        $this->form_validation->set_rules('periodisitas', 'Periodisitas', 'required|trim');

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
                    'ob' => $this->input->post('ob', true),
                    'periodisitas' => $this->input->post('periodisitas', true)
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

        // Start transaction
        $this->db->trans_start();

        // 1. Delete dependent data first
        // Delete assessments (penilaian)
        $q1 = "SELECT id FROM all_kegiatan_pencacah WHERE kegiatan_id = $id";
        $q2 = "DELETE FROM all_penilaian WHERE all_kegiatan_pencacah_id IN ($q1)";
        $this->db->query($q2);

        // 2. Delete rincian kegiatan (MISSING BEFORE)
        $this->Kegiatan_model->deletesurvei_rinciankegiatan($id);

        // 3. Delete allocation tables
        $this->Kegiatan_model->deletesurvei_all_kegiatan_pencacah($id);
        $this->Kegiatan_model->deletesurvei_all_kegiatan_pengawas($id);

        // 4. Finally delete the kegiatan itself
        $this->Kegiatan_model->deletesurvei($id);

        // Complete transaction
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Failed to delete survei! Database error.</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Survei has been deleted successfully!</div>');
        }

        redirect('kegiatan/survei');
    }

    function deletesensus($id)
    {

        // Start transaction
        $this->db->trans_start();

        // 1. Delete dependent data first
        // Delete assessments (penilaian)
        $q1 = "SELECT id FROM all_kegiatan_pencacah WHERE kegiatan_id = $id";
        $q2 = "DELETE FROM all_penilaian WHERE all_kegiatan_pencacah_id IN ($q1)";
        $this->db->query($q2);

        // 2. Delete rincian kegiatan (MISSING BEFORE)
        $this->Kegiatan_model->deletesensus_rinciankegiatan($id);

        // 3. Delete allocation tables
        $this->Kegiatan_model->deletesensus_all_kegiatan_pencacah($id);
        $this->Kegiatan_model->deletesensus_all_kegiatan_pengawas($id);

        // 4. Finally delete the kegiatan itself
        $this->Kegiatan_model->deletesensus($id);

        // Complete transaction
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Failed to delete sensus! Database error.</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Sensus has been deleted successfully!</div>');
        }

        redirect('kegiatan/sensus');
    }

    public function tambah_pencacah_organik($token)
    {
        $id = get_id_by_token('kegiatan', $token);
        if (!$id) {
            redirect('kegiatan');
        }
        $data['title'] = 'Tambah Pencacah Organik';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // Ambil data kegiatan
        $data['kegiatan'] = $this->db->get_where('kegiatan', ['id' => $id])->row_array();

        // Ambil data pegawai (organik)
        $sqlpegawai = "SELECT pegawai.* FROM pegawai";
        $data['pencacah'] = $this->db->query($sqlpegawai)->result_array();

        // Ambil pencacah organik yang sudah terpilih untuk kegiatan ini
        $assigned_query = "SELECT id_peg FROM all_kegiatan_pencacah WHERE kegiatan_id = ? AND id_peg IS NOT NULL";
        $assigned_rows = $this->db->query($assigned_query, [$id])->result_array();
        $data['assigned_peg'] = array_column($assigned_rows, 'id_peg');

        // Kuota (from all_kegiatan_pencacah)
        $data['kuota'] = $this->db->query("SELECT COUNT(kegiatan_id) AS kegiatan_id FROM all_kegiatan_pencacah WHERE kegiatan_id = ?", [$id])->row_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('kegiatan/tambah-pencacah-organik', $data);
        $this->load->view('template/footer');
    }

    public function changepencacahorganik()
    {
        $kegiatan_id = $this->input->post('kegiatanId');
        $id_peg = $this->input->post('id_peg');

        // Cek apakah sudah ada
        $check = $this->db->get_where('all_kegiatan_pencacah', [
            'kegiatan_id' => $kegiatan_id,
            'id_peg' => $id_peg
        ]);

        if ($check->num_rows() < 1) {
            // Belum ada, Insert
            $data = [
                'kegiatan_id' => $kegiatan_id,
                'id_peg' => $id_peg,
                'id_pengawas' => 0 // Default 0
            ];
            $this->db->insert('all_kegiatan_pencacah', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Pencacah Organik berhasil ditambahkan!</div>');

            // NOTIFIKASI EMAIL
            $pegawai = $this->db->get_where('pegawai', ['id_peg' => $id_peg])->row_array();
            $kegiatan = $this->db->get_where('kegiatan', ['id' => $kegiatan_id])->row_array();
            if ($pegawai && $kegiatan) {
                $subject = "Alokasi Kegiatan Baru: " . $kegiatan['nama'];
                $email_data = [
                    'nama' => $pegawai['nama'],
                    'title' => 'Penugasan Baru',
                    'message' => "<p>Anda telah ditambahkan sebagai <strong>Pencacah Organik</strong> untuk kegiatan: <strong>" . $kegiatan['nama'] . "</strong>.</p>"
                ];
                $this->_send_formatted_notification($pegawai['email'], $subject, $email_data);
            }

            // Check completion status and notify if needed
            $this->_check_and_notify_completion($kegiatan_id);

            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'success', 'message' => 'Pencacah Organik ditambahkan!', 'type' => 'add']);
                return;
            }

        } else {
            $this->db->delete('all_kegiatan_pencacah', [
                'kegiatan_id' => $kegiatan_id,
                'id_peg' => $id_peg
            ]);

            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'success', 'message' => 'Pencacah Organik dihapus!', 'type' => 'remove']);
                return;
            }
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Pencacah Organik berhasil dihapus!</div>');
        }
    }

    public function tambah_pencacah($token)
    {
        $id = get_id_by_token('kegiatan', $token);
        if (!$id) {
            redirect('kegiatan');
        }
        $data['title'] = 'Tambah Pencacah';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // Ambil data kegiatan dulu untuk dapatkan tahun
        $data['kegiatan'] = $this->db->get_where('kegiatan', ['id' => $id])->row_array();

        // Tentukan tahun kegiatan based on start date
        $tahun_kegiatan = date('Y', $data['kegiatan']['start']);
        if (!$tahun_kegiatan || $tahun_kegiatan < 2000) {
            $tahun_kegiatan = 2025; // Default fallback
        }

        // Get list of existing assignments for sync logic
        // We need all mitra active in that year, regardless if they are currently assigned or not, to show the list.

        $sql_bentuk_kegiatan = "SELECT kegiatan.ob FROM kegiatan WHERE kegiatan.id = ?";
        $bentuk_kegiatan = (int) implode($this->db->query($sql_bentuk_kegiatan, [$id])->row_array());

        // Ambil semua mitra aktif TAHUN TERKAIT, kecuali yang jadi pengawas di kegiatan ini
        $sql_id_mitra_pengawas = "SELECT id_pengawas FROM all_kegiatan_pengawas WHERE kegiatan_id = ?";
        $pengawas_ids = $this->db->query($sql_id_mitra_pengawas, [$id])->result_array();
        $ids_to_exclude = [];
        foreach ($pengawas_ids as $p) {
            if ($p['id_pengawas'])
                $ids_to_exclude[] = $p['id_pengawas'];
        }

        $exclude_clause = "";
        if (!empty($ids_to_exclude)) {
            $exclude_list = implode(',', $ids_to_exclude);
            $exclude_clause = "AND m.id_mitra NOT IN ($exclude_list)";
        }

        $sql_pencacah = "SELECT m.*, mt.posisi, mt.tahun, mt.is_active 
        FROM mitra_old m 
        JOIN mitra_tahun mt ON m.id_mitra = mt.id_mitra
        WHERE mt.is_active = 1 
        AND mt.tahun = ? 
        $exclude_clause";

        $data['pencacah'] = $this->db->query($sql_pencacah, [$tahun_kegiatan])->result_array();
        $data['kuota'] = $this->db->query("SELECT COUNT(kegiatan_id) AS kegiatan_id FROM all_kegiatan_pencacah WHERE kegiatan_id = ?", [$id])->row_array();


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
            $this->Rekap_model->sync_rincian_honor($id_mitra, $id);

            $this->session->set_flashdata('message', '<div class="alert alert-success">Pencacah berhasil ditambahkan!</div>');
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

        $sqlpencacah = "
            SELECT m.id_mitra as id_pencacah, m.nik, m.nama, m.alamat, kk.nama as kecamatan, 'mitra' as type 
            FROM all_kegiatan_pencacah akp 
            JOIN mitra m ON akp.id_mitra = m.id_mitra 
            LEFT JOIN kode_kecamatan kk ON m.kecamatan = kk.kode 
            WHERE akp.kegiatan_id = ?
            UNION ALL
            SELECT p.id_peg as id_pencacah, p.nip as nik, p.nama, p.email as alamat, '-' as kecamatan, 'pegawai' as type 
            FROM all_kegiatan_pencacah akp 
            JOIN pegawai p ON akp.id_peg = p.id_peg 
            WHERE akp.kegiatan_id = ?
        ";
        $data['pencacah'] = $this->db->query($sqlpencacah, [$id, $id])->result_array();

        $sqlkuota = "SELECT count(kegiatan_id) as kegiatan_id FROM all_kegiatan_pencacah WHERE kegiatan_id = ?";
        $data['kuota'] = $this->db->query($sqlkuota, [$id])->row_array();

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
        // AJAX Handler - kept for compatibility if needed, but bulk update supersedes this for the main page.
        // Logic remains the same.
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
        $queryemail = "SELECT email FROM mitra WHERE id_mitra = ?";
        $email = implode($this->db->query($queryemail, [$id_mitra])->row_array());

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
                $this->Rekap_model->sync_rincian_honor($id_mitra, $kegiatan_id);

                // Cek apakah email mitra sudah ada di tabel user
                $check = $this->Kegiatan_model->check_email($email);
                if ($check < 1) {
                    $this->db->insert('user', $data2);
                }

                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Pencacah berhasil ditambahkan!</div>');

                // NOTIFIKASI EMAIL
                $subject = "Alokasi Kegiatan Baru: " . $kuota['nama'];
                $nama_tujuan = 'Pencacah'; // Fallback name
                // Try to recover simplified name if needed, but 'Pencacah' is safe generic

                $email_data = [
                    'nama' => 'Pencacah', // Ideally fetch generic name or specific name logic above if complex
                    'title' => 'Penugasan Baru',
                    'message' => "<p>Anda telah ditambahkan sebagai <strong>Pencacah</strong> untuk kegiatan: <strong>" . $kuota['nama'] . "</strong>.</p>"
                ];
                $this->_send_formatted_notification($email, $subject, $email_data);

                // Check completion status and notify if needed
                $this->_check_and_notify_completion($kegiatan_id);

                if ($this->input->is_ajax_request()) {
                    // Return JSON for AJAX
                    echo json_encode(['status' => 'success', 'message' => 'Pencacah berhasil ditambahkan!', 'type' => 'add']);
                    return;
                }

            } else {
                if ($this->input->is_ajax_request()) {
                    echo json_encode(['status' => 'error', 'message' => 'Kuota pencacah penuh!']);
                    return;
                }
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
            $this->Rekap_model->sync_rincian_honor($id_mitra, $kegiatan_id);

            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'success', 'message' => 'Pencacah berhasil dihapus!', 'type' => 'remove']);
                return;
            }

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Pencacah berhasil dihapus!</div>');
        }
    }

    function tambah_pengawas($id)
    {
        $data['title'] = 'Tambah Pengawas';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $sqlpengawas = "SELECT pegawai.* FROM pegawai";
        $data['pengawas'] = $this->db->query($sqlpengawas)->result_array();

        $sqlkuota = "SELECT count(kegiatan_id) as kegiatan_id FROM all_kegiatan_pengawas WHERE kegiatan_id = ?";
        $data['kuota'] = $this->db->query($sqlkuota, [$id])->row_array();

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

        $data['kegiatan'] = $this->db->get_where('kegiatan', ['id' => $id])->row_array();

        $tahun_kegiatan = date('Y', $data['kegiatan']['start']);
        if (!$tahun_kegiatan || $tahun_kegiatan < 2000)
            $tahun_kegiatan = 2025;

        // HARDENING: Cast to integer
        $id = (int) $id;

        $sql_pengawas_mitra = "SELECT id_mitra FROM all_kegiatan_pencacah WHERE kegiatan_id = ?";

        // Use bindings
        $assigned_ids = $this->db->query($sql_pengawas_mitra, [$id])->result_array();

        $ids_to_exclude = [];
        foreach ($assigned_ids as $m) {
            if ($m['id_mitra'])
                $ids_to_exclude[] = (int) $m['id_mitra']; // Validation step
        }

        $exclude_clause = "";
        if (!empty($ids_to_exclude)) {
            // Safe implode because we cast integers above
            $exclude_list = implode(',', $ids_to_exclude);
            $exclude_clause = "AND m.id_mitra NOT IN ($exclude_list)";
        }

        // Updated Query: Join mitra_old and mitra_tahun explicitly
        $sqlpengawas = "SELECT m.*, mt.posisi, mt.tahun, mt.is_active 
            FROM mitra_old m 
            JOIN mitra_tahun mt ON m.id_mitra = mt.id_mitra
            WHERE mt.tahun = ? 
            AND mt.is_active = 1
            $exclude_clause";

        $data['pengawas'] = $this->db->query($sqlpengawas, [$tahun_kegiatan])->result_array();

        $sqlkuota = "SELECT count(kegiatan_id) as kegiatan_id FROM all_kegiatan_pengawas WHERE kegiatan_id = ?";
        $data['kuota'] = $this->db->query($sqlkuota, [$id])->row_array();

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

                // Ensure pengawas has a user entry with role 4 (pengawas)
                $sqlnamapegawai = "SELECT email FROM pegawai WHERE id_peg = $id_peg";
                $emailpegawai_row = $this->db->query($sqlnamapegawai)->row_array();
                if ($emailpegawai_row) {
                    $emailpegawai = $emailpegawai_row['email'];
                    $sqlcekpegawai = "SELECT * FROM user WHERE email = '$emailpegawai' AND role_id = 4";
                    $cekpegawai = $this->db->query($sqlcekpegawai);
                    if ($cekpegawai->num_rows() < 1) {
                        $this->db->insert('user', [
                            'email' => $emailpegawai,
                            'role_id' => '4',
                            'date_created' => time()
                        ]);
                    }
                }
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Pengawas berhasil ditambahkan!</div>');

                // NOTIFIKASI EMAIL
                if (isset($emailpegawai)) {
                    $subject = "Alokasi Kegiatan Baru: " . $kuota['nama'];
                    $email_data = [
                        'nama' => 'Pengawas',
                        'title' => 'Penugasan Baru',
                        'message' => "<p>Anda telah ditambahkan sebagai <strong>Pengawas</strong> untuk kegiatan: <strong>" . $kuota['nama'] . "</strong>.</p>"
                    ];
                    if ($this->_send_formatted_notification($emailpegawai, $subject, $email_data)) {
                        $this->db->where('id', $this->db->insert_id());
                        $this->db->update('all_kegiatan_pengawas', ['is_notified_assignment' => 1]);
                    }
                }

                // Check completion status and notify if needed
                $this->_check_and_notify_completion($kegiatan_id, $id_peg);

                if ($this->input->is_ajax_request()) {
                    echo json_encode(['status' => 'success', 'message' => 'Pengawas ditambahkan!', 'type' => 'add']);
                    return;
                }
            } else {
                if ($this->input->is_ajax_request()) {
                    echo json_encode(['status' => 'error', 'message' => 'Kuota penuh!']);
                    return;
                }
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Kuota penuh!</div>');
            }
        } else {
            $query = "UPDATE all_kegiatan_pencacah SET id_pengawas = 0 WHERE kegiatan_id = $kegiatan_id AND id_pengawas = $id_peg";
            $this->db->query($query);

            $this->db->delete('all_kegiatan_pengawas', $data);

            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'success', 'message' => 'Pengawas dihapus!', 'type' => 'remove']);
                return;
            }

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Pengawas berhasil dihapus!</div>');
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
                $this->Rekap_model->sync_rincian_honor($id_mitra, $kegiatan_id);

                // Ensure pengawas (mitra) has a user entry with role 4
                $mitra = $this->db->get_where('mitra', ['id_mitra' => $id_mitra])->row_array();
                if ($mitra) {
                    $email = $mitra['email'];
                    $sqlcek = "SELECT * FROM user WHERE email = '$email' AND role_id = 4";
                    if ($this->db->query($sqlcek)->num_rows() < 1) {
                        $this->db->insert('user', [
                            'email' => $email,
                            'role_id' => '4',
                            'date_created' => time()
                        ]);
                    }
                }
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Pengawas berhasil ditambahkan!</div>');

                // NOTIFIKASI EMAIL
                if (isset($email)) {
                    $subject = "Alokasi Kegiatan Baru: " . $kuota['nama'];
                    $email_data = [
                        'nama' => 'Pengawas',
                        'title' => 'Penugasan Baru',
                        'message' => "<p>Anda telah ditambahkan sebagai <strong>Pengawas (Mitra)</strong> untuk kegiatan: <strong>" . $kuota['nama'] . "</strong>.</p>"
                    ];
                    if ($this->_send_formatted_notification($email, $subject, $email_data)) {
                        $this->db->where('id', $this->db->insert_id());
                        $this->db->update('all_kegiatan_pengawas', ['is_notified_assignment' => 1]);
                    }
                }

                // Check completion status and notify if needed
                $this->_check_and_notify_completion($kegiatan_id, $id_mitra);

                if ($this->input->is_ajax_request()) {
                    echo json_encode(['status' => 'success', 'message' => 'Pengawas (Mitra) ditambahkan!', 'type' => 'add']);
                    return;
                }
            } else {
                if ($this->input->is_ajax_request()) {
                    echo json_encode(['status' => 'error', 'message' => 'Kuota penuh!']);
                    return;
                }
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Kuota pengawas penuh!</div>');
            }
        }
        // Jika sudah diassign, maka batalkan dan hapus semua yang terkait
        else {
            // Kosongkan referensi pengawas di all_kegiatan_pencacah
            $this->db->query("UPDATE all_kegiatan_pencacah SET id_pengawas = 0 WHERE kegiatan_id = ? AND id_pengawas = ?", [$kegiatan_id, $id_mitra]);

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
            $this->Rekap_model->sync_rincian_honor($id_mitra, $kegiatan_id);

            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'success', 'message' => 'Pengawas (Mitra) dihapus!', 'type' => 'remove']);
                return;
            }

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


    function details_kegiatan_pengawas($id, $id_pengawas)
    {
        $data['title'] = 'Details Kegiatan';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $now = time();

        $sql = "SELECT all_kegiatan_pengawas.*, kegiatan.* FROM all_kegiatan_pengawas INNER JOIN kegiatan ON all_kegiatan_pengawas.kegiatan_id = kegiatan.id WHERE all_kegiatan_pengawas.id_pengawas = $id_pengawas AND ((kegiatan.start <= $now AND kegiatan.finish >= $now) OR (kegiatan.start > $now)) ORDER BY kegiatan.start";
        $data['details'] = $this->db->query($sql)->result_array();

        $jumlahkegiatan = count($data['details']);

        if ($jumlahkegiatan > 0) {

            $data['pengawas'] = $this->db->get_where('pegawai', ['id_peg' => $id_pengawas])->row_array();

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

    function details_kegiatan_pengawas_mitra($kegiatan_id, $id_pengawas)
    {
        $data['title'] = 'Details Kegiatan';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $now = time();

        $sql = "SELECT all_kegiatan_pengawas.*, kegiatan.* FROM all_kegiatan_pengawas INNER JOIN kegiatan ON all_kegiatan_pengawas.kegiatan_id = kegiatan.id WHERE all_kegiatan_pengawas.id_pengawas = $id_pengawas AND ((kegiatan.start <= $now AND kegiatan.finish >= $now) OR (kegiatan.start > $now)) ORDER BY kegiatan.start";
        $data['details'] = $this->db->query($sql)->result_array();

        $jumlahkegiatan = count($data['details']);

        if ($jumlahkegiatan > 0) {

            $data['pengawas'] = $this->db->get_where('mitra', ['id_mitra' => $id_pengawas])->row_array();

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

        // UNION ALL for Mitra and Pegawai (Organik)
        $sqlpencacah = "
            SELECT akp.id_mitra as id_pencacah, m.nama, m.nik, 'mitra' as type 
            FROM all_kegiatan_pencacah akp 
            JOIN mitra m ON akp.id_mitra = m.id_mitra 
            WHERE akp.kegiatan_id = $kegiatan_id AND (akp.id_pengawas = 0 OR akp.id_pengawas IS NULL)
            UNION ALL
            SELECT akp.id_peg as id_pencacah, p.nama, p.nip as nik, 'pegawai' as type 
            FROM all_kegiatan_pencacah akp 
            JOIN pegawai p ON akp.id_peg = p.id_peg 
            WHERE akp.kegiatan_id = $kegiatan_id AND (akp.id_pengawas = 0 OR akp.id_pengawas IS NULL)
        ";
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
        $log_msg = "DEBUG CHANGEPENCACAHPENGAWAS EXECUTED AT " . date('Y-m-d H:i:s') . "\n";
        file_put_contents('application/logs/custom_assignment_debug.txt', $log_msg, FILE_APPEND);
        $kegiatan_id = $this->input->post('kegiatanId');
        $id_pengawas = $this->input->post('id_pengawas'); // ID Pengawas (Pegawai/Mitra)
        $id_pencacah = $this->input->post('id_pencacah'); // ID Pencacah (Target)
        $type = $this->input->post('type'); // 'mitra' or 'pegawai'

        $where = [
            'kegiatan_id' => $kegiatan_id,
        ];

        if ($type == 'pegawai') {
            $where['id_peg'] = $id_pencacah;
        } else {
            $where['id_mitra'] = $id_pencacah;
        }

        // Cek current state
        $current = $this->db->get_where('all_kegiatan_pencacah', $where)->row_array();

        if ($current && $current['id_pengawas'] == 0) {
            // Assign Pengawas
            $this->db->update('all_kegiatan_pencacah', ['id_pengawas' => $id_pengawas], $where);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Pencacah assigned!</div>');

            file_put_contents('application/logs/custom_assignment_debug.txt', "Assigning ID Pengawas: $id_pengawas to Pencacah: $id_pencacah ($type)\n", FILE_APPEND);

            // --- EMAIL NOTIFICATION START ---
            // 1. Get Activity Info
            $kegiatan = $this->db->get_where('kegiatan', ['id' => $kegiatan_id])->row_array();

            // 2. Get Pengawas Info (Could be in Pegawai or Mitra table)
            // Try Pegawai first
            $pengawas = $this->db->get_where('pegawai', ['id_peg' => $id_pengawas])->row_array();
            if (!$pengawas) {
                $pengawas = $this->db->get_where('mitra', ['id_mitra' => $id_pengawas])->row_array();
            }

            // 3. Get Pencacah Info
            if ($type == 'pegawai') {
                $pencacah = $this->db->get_where('pegawai', ['id_peg' => $id_pencacah])->row_array();
            } else {
                $pencacah = $this->db->get_where('mitra', ['id_mitra' => $id_pencacah])->row_array();
            }

            // 4. Send Emails if data exists
            if ($kegiatan && $pengawas && $pencacah) {
                $subject = "Pemberitahuan Kegiatan: " . $kegiatan['nama'];

                // Email to Pengawas
                if (!empty($pengawas['email'])) {
                    $email_data = [
                        'nama' => $pengawas['nama'],
                        'title' => 'Penugasan Baru',
                        'message' => "
                            <p>Anda telah ditunjuk sebagai <strong>Pengawas</strong> untuk kegiatan: <strong>{$kegiatan['nama']}</strong>.</p>
                            <p>Anda akan mengawasi Pencacah: <strong>{$pencacah['nama']}</strong>.</p>
                        "
                    ];
                    $this->_send_formatted_notification($pengawas['email'], $subject, $email_data);
                }

                // Email to Pencacah
                if (!empty($pencacah['email'])) {
                    $email_data = [
                        'nama' => $pencacah['nama'],
                        'title' => 'Penugasan Baru',
                        'message' => "
                            <p>Anda telah terdaftar sebagai <strong>Pencacah</strong> untuk kegiatan: <strong>{$kegiatan['nama']}</strong>.</p>
                            <p>Pengawas Anda adalah: <strong>{$pengawas['nama']}</strong>.</p>
                        "
                    ];
                    $this->_send_formatted_notification($pencacah['email'], $subject, $email_data);
                }
            }
            // --- EMAIL NOTIFICATION END ---

            // Check completion status and notify if needed
            $this->_check_and_notify_completion($kegiatan_id, $id_pengawas);

        } elseif ($current && $current['id_pengawas'] == $id_pengawas) {
            // Unassign (toggle off)
            $this->db->update('all_kegiatan_pencacah', ['id_pengawas' => 0], $where);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Pencacah unassigned!</div>');
        }
    }

    function pencacahterpilih($kegiatan_token, $id_peg)
    {
        $kegiatan_id = get_id_by_token('kegiatan', $kegiatan_token);
        if (!$kegiatan_id) {
            redirect('kegiatan');
        }
        $data['title'] = 'Pencacah Terpilih';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $sqlpengawas = "SELECT pegawai.id_peg as id_peg, pegawai.nama as nama, pegawai.email as email FROM pegawai WHERE id_peg = $id_peg UNION SELECT mitra.id_mitra as id_peg, mitra.nama as nama, mitra.email as email FROM mitra WHERE id_mitra = $id_peg ";
        $data['pengawas'] = $this->db->query($sqlpengawas)->row_array();

        $sqlpencacah = "
            SELECT akp.id_mitra as id_pencacah, mitra.nama, mitra.nik, 'mitra' as type 
            FROM all_kegiatan_pencacah akp 
            JOIN mitra ON akp.id_mitra = mitra.id_mitra 
            WHERE (akp.kegiatan_id = $kegiatan_id) AND (akp.id_pengawas = $id_peg)
            UNION ALL
            SELECT akp.id_peg as id_pencacah, pegawai.nama, pegawai.nip as nik, 'pegawai' as type 
            FROM all_kegiatan_pencacah akp 
            JOIN pegawai ON akp.id_peg = pegawai.id_peg 
            WHERE (akp.kegiatan_id = $kegiatan_id) AND (akp.id_pengawas = $id_peg)
        ";
        $data['pencacah'] = $this->db->query($sqlpencacah)->result_array();

        $data['kegiatan'] = $this->db->get_where('kegiatan', ['id' => $kegiatan_id])->row_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('kegiatan/pencacah-terpilih', $data);
        $this->load->view('template/footer');
    }



    private function _check_and_notify_completion($kegiatan_id, $id_pengawas_target = null)
    {
        // 1. Get Activity
        $kegiatan = $this->db->get_where('kegiatan', ['id' => $kegiatan_id])->row_array();
        if (!$kegiatan)
            return;

        // 2. Filter New Activities Only (Prevent spamming old data)
        // Check if created_at exists and is greater than ~Jan 1 2026 timestamp (1767225600)
        // or just check if it is not null if we trust the migration.
        if (empty($kegiatan['created_at'])) {
            return;
        }

        // 3. Logic for "Completion" (Penilaian) Notification
        // Trigger ONLY if Activity Status is FINISHED (Selesai) or End Date < Now
        $now = time();
        $is_finished = ($kegiatan['finish'] < $now) || (isset($kegiatan['status']) && $kegiatan['status'] == 'Selesai');

        if ($is_finished) {
            // Get Supervisors who haven't been notified of completion
            // If target specific pengawas (e.g. from assignment action), check just them
            $where = ['kegiatan_id' => $kegiatan_id, 'is_notified_completion' => 0];
            if ($id_pengawas_target) {
                $where['id_pengawas'] = $id_pengawas_target;
            }

            $pengawas_list = $this->db->get_where('all_kegiatan_pengawas', $where)->result_array();

            foreach ($pengawas_list as $p_row) {
                $id_pengawas = $p_row['id_pengawas'];

                // Get Email
                $pengawas = $this->db->get_where('pegawai', ['id_peg' => $id_pengawas])->row_array();
                if (!$pengawas) {
                    $pengawas = $this->db->get_where('mitra', ['id_mitra' => $id_pengawas])->row_array();
                }

                if ($pengawas && !empty($pengawas['email'])) {
                    $subject = "Pemberitahuan Penilaian Kegiatan: " . $kegiatan['nama'];
                    $email_data = [
                        'nama' => $pengawas['nama'],
                        'title' => 'Kegiatan Selesai',
                        'message' => "
                            <p>Kegiatan <strong>{$kegiatan['nama']}</strong> telah berstatus <strong>Selesai</strong>.</p>
                            <p>Silakan login untuk melakukan penilaian kinerja mitra.</p>
                            <br>
                            <a href='" . base_url('penilaian') . "' style='background-color: #4e73df; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Beri Penilaian</a>
                        "
                    ];

                    // Slight delay to ensure ordering
                    sleep(2);

                    if ($this->_send_formatted_notification($pengawas['email'], $subject, $email_data)) {
                        // Mark as notified
                        $this->db->where('id', $p_row['id']);
                        $this->db->update('all_kegiatan_pengawas', ['is_notified_completion' => 1]);
                    }
                }
            }
        }
    }

    private function _send_formatted_notification($to, $subject, $data)
    {
        // 1. Send Email (Existing Logic)
        // $data should contain: 'nama', 'title', 'message', 'link' (optional)
        $message = $this->load->view('emails/notification', $data, true);
        $email_sent = $this->_send_notification($to, $subject, $message);

        // 2. Insert into DB Notification System
        // Need to check if $to is a user in our system (which it should be)
        // Check Pegawai or Mitra table to ensure consistency? 
        // Actually, 'user' table uses email as key often.
        // We will just store the email, and filtering happens in get_latest().

        $notif_insert = [
            'user_email' => $to,
            'title' => isset($data['title']) ? $data['title'] : 'Notification',
            'message' => isset($data['message']) ? $data['message'] : 'You have a new notification.',
            'link' => isset($data['link']) ? $data['link'] : '#', // Add link if available
            'icon' => 'fas fa-envelope', // Default icon
            'color' => 'success', // Default color
            'is_read' => 0
        ];

        if (strpos($subject, 'Penugasan') !== false) {
            $notif_insert['icon'] = 'fas fa-tasks';
            $notif_insert['color'] = 'primary';
        } elseif (strpos($subject, 'Selesai') !== false) {
            $notif_insert['icon'] = 'fas fa-check-circle';
            $notif_insert['color'] = 'success';
        }

        $this->db->insert('notifications', $notif_insert);

        return $email_sent;
    }

    private function _send_notification($to, $subject, $message)
    {
        // Load email library if not loaded
        if (!isset($this->email)) {
            $this->load->library('email');
        }

        // Config from TestEmail.php
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.hostinger.com',
            'smtp_user' => 'admin@bps-batanghari.com',
            'smtp_pass' => 'Zxcxz12321.',
            'smtp_port' => 587,
            'smtp_crypto' => 'tls',
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'crlf' => "\r\n",
            'validation' => TRUE,
            'wordwrap' => TRUE,
            'smtp_timeout' => 20 // Added timeout to prevent drops
        );

        // Debugging config usage
        log_message('error', 'DEBUG: Initializing Email with Config in _send_notification');

        $this->email->initialize($config);
        $this->email->set_newline("\r\n");

        $this->email->from('admin@bps-batanghari.com', 'Admin SPANENG');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        if ($this->email->send()) {
            log_message('error', 'DEBUG: Email sent successfully to ' . $to);
            return true;
        } else {
            // Log error for debugging
            log_message('error', 'Email error: ' . $this->email->print_debugger());
            return false;
        }
    }

}