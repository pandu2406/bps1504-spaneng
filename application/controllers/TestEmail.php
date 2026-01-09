<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TestEmail extends CI_Controller
{

    public function index()
    {
        // Inline config to verify credentials directly
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

        $this->load->library('email');
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");

        $this->email->from('admin@bps-batanghari.com', 'Admin SPANENG');
        $this->email->to('maulana.pandudinata@bps.go.id');
        $this->email->subject('Test Email SMTP Hostinger - SPANENG (Inline Config)');
        $this->email->message('Ini adalah email percobaan dengan konfigurasi inline untuk memverifikasi kredensial.');

        if ($this->email->send()) {
            echo "Email berhasil dikirim ke maulana.pandudinata@bps.go.id";
        } else {
            echo "Email GAGAL dikirim.<br>";
            echo "<pre>";
            echo $this->email->print_debugger();
            echo "</pre>";
        }
    }
}
