<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Konfigurasi Email (SMTP)
// Silakan sesuaikan dengan akun email yang digunakan untuk pengiriman notifikasi.
// Untuk Gmail, pastikan menggunakan "App Password" jika 2FA aktif.

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
