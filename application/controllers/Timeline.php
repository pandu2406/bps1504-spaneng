<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Timeline extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Timeline_model');
    }

    public function index()
    {
        $data['title'] = 'Jadwal';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $role_id = $data['user']['role_id'];
        $now = time();
        $email = $data['user']['email'];

        if ($role_id == 5) {
            $sql_email = "SELECT id_mitra FROM mitra WHERE email LIKE '$email'";
            $id_mitra = implode($this->db->query($sql_email)->row_array());
            $sql_kegiatan = "SELECT kegiatan.* FROM kegiatan JOIN all_kegiatan_pencacah ON all_kegiatan_pencacah.kegiatan_id = kegiatan.id WHERE all_kegiatan_pencacah.id_mitra = $id_mitra AND ((kegiatan.start <= $now AND kegiatan.finish >= $now) OR (kegiatan.start > $now)) ORDER BY kegiatan.start";
            $data['jlhk'] = $this->db->query($sql_kegiatan)->num_rows();
            $data['kegiatan'] = $this->db->query($sql_kegiatan)->result_array();
        } elseif ($role_id == 4) {
            $sql_id_peg = "SELECT id_peg FROM pegawai WHERE email LIKE '$email' UNION (SELECT id_mitra as id_peg FROM mitra WHERE email LIKE '$email')";
            $id_peg = implode($this->db->query($sql_id_peg)->row_array());
            $sql_kegiatan = "SELECT kegiatan.* FROM kegiatan JOIN all_kegiatan_pengawas ON all_kegiatan_pengawas.kegiatan_id = kegiatan.id WHERE all_kegiatan_pengawas.id_pengawas = $id_peg AND ((kegiatan.start <= $now AND kegiatan.finish >= $now) OR (kegiatan.start > $now))ORDER BY kegiatan.start";
            $data['jlhk'] = $this->db->query($sql_kegiatan)->num_rows();
            $data['kegiatan'] = $this->db->query($sql_kegiatan)->result_array();
        } elseif ($role_id <= 2) {
            $sql_kegiatan = "SELECT * FROM kegiatan WHERE ((start <= $now AND finish >= $now) OR (start > $now)) ORDER BY kegiatan.start";
            $data['jlhk'] = $this->db->query($sql_kegiatan)->num_rows();
            $data['kegiatan'] = $this->db->query($sql_kegiatan)->result_array();
        } else {
            $seksi_id = $data['user']['seksi_id'];
            $sql_kegiatan = "SELECT * FROM kegiatan WHERE seksi_id = $seksi_id AND ((start <= $now AND finish >= $now) OR (start > $now)) ORDER BY kegiatan.start";
            $data['jlhk'] = $this->db->query($sql_kegiatan)->num_rows();
            $data['kegiatan'] = $this->db->query($sql_kegiatan)->result_array();
        }

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('timeline/index', $data);
        $this->load->view('template/footer');
    }
}
