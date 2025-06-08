<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in_user();
    }

    public function index()
    {
        $data['title'] = 'My Profile';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $email = $this->session->userdata('email');

        $sqlpegawai = "SELECT pegawai.nama as nama, pegawai.email as email FROM pegawai WHERE pegawai.email = '$email' UNION (SELECT mitra.nama as nama, mitra.email as email FROM mitra WHERE mitra.email = '$email')";
        $data['pegawai'] = $this->db->query($sqlpegawai)->row_array();
        $data['mitra'] = $this->db->get_where('mitra', ['email' => $this->session->userdata('email')])->row_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('user/index', $data);
        $this->load->view('template/footer');
    }

    public function edit()
    {
        $data['title'] = 'Edit Profile';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('email', 'Email', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('user/edit', $data);
            $this->load->view('template/footer');
        } else {
            $email = $this->input->post('email');

            $upload_image = $_FILES['image'];


            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '2048';
                $config['upload_path'] = './assets/img/profile/';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $old_image = $data['user']['image'];
                    if ($old_image != 'default.jpg') {
                        unlink(FCPATH . 'assets/img/profile/' . $old_image);
                    }

                    $new_image = $this->upload->data('file_name');
                    $this->db->set('image', $new_image);
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $this->upload->display_errors() . '</div>');
                    redirect('user');
                }
            }

            $this->db->set('email', $email);
            $this->db->where('email', $email);
            $this->db->update('user');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Your profile has been updated!</div>');
            redirect('user');
        }
    }

    public function editprofilemitra()
    {
        $data['title'] = 'Edit Profile';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['mitra'] = $this->db->get_where('mitra', ['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat');
        $this->form_validation->set_rules('no_hp', 'No. HP', 'required');
        $this->form_validation->set_rules('pendidikan', 'Pendidikan');
        $this->form_validation->set_rules('pekerjaan', 'Pekerjaan Utama');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('user/editprofilemitra', $data);
            $this->load->view('template/footer');
        } else {

            $email = $this->input->post('email');
            $data = [

                'nama' => $this->input->post('nama'),
                'email' => $this->input->post('email'),
                'alamat' => $this->input->post('alamat'),
                'no_hp' => $this->input->post('no_hp'),
                'pendidikan' => $this->input->post('pendidikan'),
                'pekerjaan' => $this->input->post('pekerjaan')
            ];

            $data2 = [
                'email' => $this->input->post('email')
            ];

            // $upload_image = $_FILES['image'];

            // if ($upload_image) {
            //     $config['allowed_types'] = 'gif|jpg|png';
            //     $config['max_size'] = '2048';
            //     $config['upload_path'] = './assets/img/profile/';

            //     $this->load->library('upload', $config);

            //     if ($this->upload->do_upload('image')) {
            //         $old_image = $data['user']['image'];
            //         if ($old_image != 'default.jpg') {
            //             unlink(FCPATH . 'assets/img/profile/' . $old_image);
            //         }

            //         $new_image = $this->upload->data('file_name');
            //         $this->db->set('image', $new_image);
            //         $this->db->where('email', $email);
            //         $this->db->update('user');
            //     } else {
            //         $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $this->upload->display_errors() . '</div>');
            //         redirect('user');
            //     }
            // }

            $this->db->set($data);
            $this->db->where('email', $email);
            $this->db->update('mitra');

            $this->db->set($data2);
            $this->db->where('email', $email);
            $this->db->update('user');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Your profile has been updated!</div>');
            redirect('user');
        }
    }

    public function changePassword()
    {
        $data['title'] = 'Change Password';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('current_password', 'Current Password', 'required|trim');
        $this->form_validation->set_rules('new_password1', 'New Password', 'required|trim|min_length[8]|matches[new_password2]');
        $this->form_validation->set_rules('new_password2', 'Confirm New Password', 'required|trim|min_length[8]|matches[new_password1]');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('user/changepassword', $data);
            $this->load->view('template/footer');
        } else {
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password1');
            if (!password_verify($current_password, $data['user']['password'])) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong current password!</div>');
                redirect('user/changepassword');
            } else {
                if ($current_password == $new_password) {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">New password cannot be the same as current password!</div>');
                    redirect('user/changepassword');
                } else {
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                    $this->db->set('password', $password_hash);
                    $this->db->where('email', $this->session->userdata('email'));
                    $this->db->update('user');

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Password changed!</div>');
                    redirect('user/changepassword');
                }
            }
        }
    }
}
