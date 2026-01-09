<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DebugEmail extends CI_Controller
{

    public function index()
    {
        echo "<h1>Email Debugger</h1>";

        // Load library
        $this->load->library('email');

        // Exact config from Kegiatan.php
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.hostinger.com',
            'smtp_user' => 'admin@bps-batanghari.com',
            'smtp_pass' => 'Zxcxz12321.',
            'smtp_port' => 587,
            'smtp_crypto' => 'tls',
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n",
            'crlf' => "\r\n",
            'validation' => TRUE,
            'wordwrap' => TRUE
        );

        // Debug output config
        echo "<h3>Configuration:</h3><pre>";
        print_r($config);
        echo "</pre>";

        $this->email->initialize($config);
        $this->email->set_newline("\r\n");

        $this->email->from('admin@bps-batanghari.com', 'Admin SPANENG Debug');
        // Sending to the user's apparent email or self
        $this->email->to('admin@bps-batanghari.com');
        $this->email->subject('Debug Email ' . date('Y-m-d H:i:s'));
        $this->email->message('Testing email from DebugEmail controller.');

        if ($this->email->send()) {
            echo "<h2 style='color:green'>SUCCESS</h2>";
        } else {
            echo "<h2 style='color:red'>FAILURE</h2>";
            echo "<pre>" . $this->email->print_debugger() . "</pre>";
        }
    }
}
