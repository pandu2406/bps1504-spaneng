<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Role_model');
        $this->load->model('Admin_model');
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['mitra'] = $this->db->get('mitra')->num_rows();
        $data['pegawai'] = $this->db->get('pegawai')->num_rows();

        $now = time();

        $sql_k_berjalan = "SELECT * FROM kegiatan WHERE start <= $now AND finish >= $now";
        $data['k_berjalan'] = $this->db->query($sql_k_berjalan)->num_rows();

        $sql_k_akan_datang = "SELECT * FROM kegiatan WHERE start > $now";
        $data['k_akan_datang'] = $this->db->query($sql_k_akan_datang)->num_rows();

        $sql = "SELECT kegiatan.* FROM kegiatan WHERE ((start <= $now AND finish >= $now) OR (start > $now)) ORDER BY start";

        $data['jlhk'] = $this->db->query($sql)->num_rows();

        $data['details'] = $this->db->query($sql)->result_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('template/footer');
    }

    public function role()
    {
        $data['title'] = 'Role';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['role'] = $this->db->get('user_role')->result_array();

        $this->form_validation->set_rules('role', 'Role', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('admin/role', $data);
            $this->load->view('template/footer');
        } else {
            $this->db->insert('user_role', ['role' => $this->input->post('role')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New role added!</div>');
            redirect('admin/role');
        }
    }

    public function editrole($id)
    {
        $data['title'] = 'Edit Role';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['role'] = $this->db->get_where('user_role', ['id' => $id])->row_array();

        $this->form_validation->set_rules('role', 'Role', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('admin/edit-role', $data);
            $this->load->view('template/footer');
        } else {

            $role = $this->input->post('role');


            $this->db->set('role', $role);
            $this->db->where('id', $id);
            $this->db->update('user_role');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Role has been updated!</div>');
            redirect('admin/role');
        }
    }

    function deleterole($id)
    {
        $this->Role_model->delete($id);
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Role has been deleted!</div>');
        redirect('admin/role');
    }

    public function roleaccess($role_id)
    {
        $data['title'] = 'Role Access';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

        $this->db->where('id !=', 6);
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('admin/role-access', $data);
        $this->load->view('template/footer');
    }

    public function changeaccess()
    {
        $menu_id = $this->input->post('menuId');
        $role_id = $this->input->post('roleId');

        $data = [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ];

        $result = $this->db->get_where('user_access_menu', $data);

        if ($result->num_rows() < 1) {
            $this->db->insert('user_access_menu', $data);
        } else {
            $this->db->delete('user_access_menu', $data);
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Access changed!</div>');
    }

    public function alluser()
    {
        $data['title'] = 'All User';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $query = "SELECT user.*, user_role.role FROM user LEFT JOIN user_role ON user.role_id = user_role.id";
        $data['alluser'] = $this->db->query($query)->result_array();
        // $data['role'] = $this->db->get('user_role')->result_array();
        // $data['seksi'] = $this->db->get('seksi')->result_array();
        // $data['pegawai'] = $this->db->get('pegawai')->result_array();


        // $this->form_validation->set_rules('email', 'Email', 'required|trim');
        // $this->form_validation->set_rules('role_id', 'Role_id', 'required|trim');

        // if ($this->form_validation->run() == false) {
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('admin/all_user', $data);
        $this->load->view('template/footer');
        // } else {
        //     $data = [

        //         'email' => $this->input->post('email'),
        //         'role_id' => $this->input->post('role_id'),
        //         'seksi_id' => $this->input->post('seksi_id'),
        //         'date_created' => time()

        //     ];

        //     $email = $this->input->post('email');
        //     $role_id = $this->input->post('role_id');

        //     $sqlcek = "SELECT * FROM user WHERE email = '$email' AND role_id = $role_id";
        //     $cek = $this->db->query($sqlcek)->num_rows();

        //     if ($cek < 1) {
        //         $this->db->insert('user', $data);
        //         $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New user added!</div>');
        //     } else {
        //         $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">User sudah ada!</div>');
        //     }

        //     redirect('admin/alluser');
        // }
    }

    public function deactivated($id)
    {
        $this->Admin_model->deactivated($id);
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">User has been deactivated!</div>');
        redirect('admin/alluser');
    }

    public function activated($id)
    {
        $this->Admin_model->activated($id);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">User has been activated!</div>');
        redirect('admin/alluser');
    }

    public function deleteuser($id)
    {
        $this->Admin_model->delete($id);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">User has been deleted!</div>');
        redirect('admin/alluser');
    }
}
