<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Menu_model');
    }

    public function index()
    {
        $data['title'] = 'Menu Management';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('menu', 'Menu', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('template/footer');
        } else {
            $this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New menu added!</div>');
            redirect('menu');
        }
    }

    public function submenu()
    {
        $data['title'] = 'Submenu Management';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->model('Menu_model', 'menu');

        $data['subMenu'] = $this->menu->getSubMenu();
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('menu_id', 'Menu', 'required');
        $this->form_validation->set_rules('url', 'URL', 'required');
        $this->form_validation->set_rules('icon', 'Icon', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('menu/submenu', $data);
            $this->load->view('template/footer');
        } else {
            $data = [
                'title' => $this->input->post('title'),
                'menu_id' => $this->input->post('menu_id'),
                'url' => $this->input->post('url'),
                'icon' => $this->input->post('icon'),
                'is_active' => $this->input->post('is_active'),
            ];
            $this->db->insert('user_sub_menu', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New submenu added!</div>');
            redirect('menu/submenu');
        }
    }

    public function editmenu($id)
    {
        $data['title'] = 'Edit Menu';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['menu'] = $this->db->get_where('user_menu', ['id' => $id])->row_array();

        $this->form_validation->set_rules('menu', 'Menu', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('menu/edit-menu', $data);
            $this->load->view('template/footer');
        } else {

            $menu = $this->input->post('menu');
            $id = $this->input->post('id');

            $this->db->set('menu', $menu);
            $this->db->where('id', $id);
            $this->db->update('user_menu');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Menu has been updated!</div>');
            redirect('menu');
        }
    }

    public function editsubmenu($id)
    {
        $data['title'] = 'Edit Submenu';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $query_submenu = "SELECT user_sub_menu.*, user_menu.menu FROM user_sub_menu JOIN user_menu ON user_sub_menu.menu_id = user_menu.id WHERE user_sub_menu.id = $id";
        $data['submenu'] = $this->db->query($query_submenu)->row_array();

        $this->form_validation->set_rules('title', 'Title', 'required|trim');
        $this->form_validation->set_rules('url', 'Url', 'required|trim');
        $this->form_validation->set_rules('icon', 'Icon', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('menu/edit-submenu', $data);
            $this->load->view('template/footer');
        } else {
            $data = [
                'title' => $this->input->post('title'),
                'url' => $this->input->post('url'),
                'icon' => $this->input->post('icon')
            ];

            $this->db->set($data);
            $this->db->where('id', $id);
            $this->db->update('user_sub_menu');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Menu has been updated!</div>');
            redirect('menu/submenu');
        }
    }

    function deletemenu($id)
    {
        $this->Menu_model->deletemenu($id);
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Menu has been deleted!</div>');
        redirect('menu');
    }

    function deletesubmenu($id)
    {
        $this->Menu_model->deletesubmenu($id);
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Sub Menu has been deleted!</div>');
        redirect('menu/submenu');
    }

    public function deactivated($id)
    {
        $this->Menu_model->deactivated($id);
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Submenu has been deactivated!</div>');
        redirect('menu/submenu');
    }

    public function activated($id)
    {
        $this->Menu_model->activated($id);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Submenu has been activated!</div>');
        redirect('menu/submenu');
    }
}
