# 📊 SPANENG - Sistem Penilaian dan Evaluasi Beban Kerja Mitra

<div align="center">

![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4.svg?logo=php&logoColor=white)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-3.1.11-EF4223.svg?logo=codeigniter&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1.svg?logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-4.6-7952B3.svg?logo=bootstrap&logoColor=white)
![AdminLTE](https://img.shields.io/badge/AdminLTE-3.2-3C8DBC.svg)
![License](https://img.shields.io/badge/License-MIT-yellow.svg)

**Sistem manajemen penilaian dan evaluasi beban kerja mitra BPS yang komprehensif dengan fitur lengkap untuk pengelolaan kegiatan, penilaian, ranking, dan pelaporan**

[Fitur](#-fitur-utama) • [Instalasi](#-instalasi) • [Penggunaan](#-panduan-penggunaan) • [Dokumentasi](#-dokumentasi-api)

</div>

---

## 🎯 Tentang Aplikasi

**SPANENG (Sistem Penilaian dan Evaluasi Beban Kerja Mitra)** adalah aplikasi web berbasis CodeIgniter 3 yang dirancang khusus untuk BPS (Badan Pusat Statistik) dalam mengelola dan mengevaluasi kinerja mitra statistik. Aplikasi ini menyediakan platform terintegrasi untuk:

- 📋 **Manajemen Kegiatan** - Kelola kegiatan survei dan sensus
- ⭐ **Sistem Penilaian** - Evaluasi kinerja mitra secara objektif
- 🏆 **Ranking Otomatis** - Peringkat mitra berdasarkan performa
- 📄 **Persuratan Digital** - Kelola dokumen LPD (Laporan Perjalanan Dinas)
- 📊 **Rekap & Laporan** - Dashboard dan reporting komprehensif
- 👥 **Multi-User Management** - Role-based access control

---

## ✨ Fitur Utama

### 🔐 **Autentikasi & Otorisasi**
- Login system dengan session management
- Role-based access control (Admin, Supervisor, User)
- Password encryption dengan bcrypt
- CSRF protection terintegrasi
- Session timeout otomatis

### 📋 **Manajemen Kegiatan**
- **CRUD Kegiatan** - Create, Read, Update, Delete kegiatan
- **Import Excel** - Upload data kegiatan massal via Excel
- **Export Data** - Download data kegiatan ke Excel
- **Filter & Search** - Pencarian dan filter berdasarkan berbagai kriteria
- **Status Tracking** - Monitor status kegiatan (Aktif, Selesai, Dibatalkan)
- **Assignment Mitra** - Assign mitra ke kegiatan tertentu

### ⭐ **Sistem Penilaian**
- **Multi-Kriteria Penilaian**:
  - Kualitas data
  - Ketepatan waktu
  - Kelengkapan dokumen
  - Kepatuhan SOP
  - Komunikasi & koordinasi
- **Penilaian Bertingkat** - Supervisor → Admin approval
- **History Penilaian** - Tracking semua penilaian
- **Auto-Calculation** - Perhitungan skor otomatis
- **Validasi Data** - Validasi input untuk konsistensi

### 🏆 **Ranking & Leaderboard**
- **Ranking Otomatis** - Berdasarkan total skor penilaian
- **Filter Periode** - Ranking per bulan/tahun
- **Filter Kegiatan** - Ranking per jenis kegiatan
- **Top Performers** - Highlight mitra terbaik
- **Statistik Performa** - Grafik dan chart performa
- **Export Ranking** - Download ranking ke PDF/Excel

### 📄 **Persuratan (LPD Management)**
- **LPD Pegawai** - Laporan Perjalanan Dinas untuk pegawai
- **LPD Mitra** - Laporan Perjalanan Dinas untuk mitra
- **Mass Import** - Upload LPD massal via Excel
- **Template Excel** - Download template untuk import
- **Validasi Otomatis** - Cek kelengkapan data LPD
- **Print/Export** - Cetak atau export LPD

### 📊 **Rekap & Dashboard**
- **Dashboard Interaktif** - Overview statistik real-time
- **Rekap Kegiatan** - Summary kegiatan per periode
- **Rekap Penilaian** - Summary penilaian per mitra
- **Rekap Mitra** - Data lengkap mitra dan performanya
- **Chart & Grafik** - Visualisasi data dengan Chart.js
- **Export Laporan** - Download laporan ke Excel/PDF

### 👥 **Master Data Management**
- **Manajemen User** - CRUD user dengan role assignment
- **Manajemen Mitra** - Database mitra lengkap
- **Manajemen Pegawai** - Data pegawai BPS
- **Manajemen Kegiatan Master** - Template kegiatan
- **Manajemen Kriteria** - Kriteria penilaian
- **Manajemen Satuan Kerja** - Organisasi BPS

### 🔔 **Notifikasi**
- **Real-time Notifications** - Notifikasi kegiatan dan penilaian
- **Email Notifications** - Email otomatis untuk event penting
- **Notification Center** - Dashboard notifikasi terpusat
- **Mark as Read** - Tandai notifikasi sudah dibaca

---

## 🛠️ Teknologi yang Digunakan

### **Backend**
| Technology | Version | Purpose |
|------------|---------|---------|
| 🐘 PHP | 7.4+ | Server-side Language |
| 🔥 CodeIgniter | 3.1.11 | MVC Framework |
| 🗄️ MySQL | 5.7+ | Database Management |
| 📧 PHPMailer | Latest | Email Sending |
| 📊 PHPExcel | Latest | Excel Import/Export |
| 🔐 Bcrypt | Built-in | Password Hashing |

### **Frontend**
| Technology | Version | Purpose |
|------------|---------|---------|
| 🎨 AdminLTE | 3.2 | Admin Dashboard Template |
| 🅱️ Bootstrap | 4.6 | CSS Framework |
| 📊 Chart.js | 3.x | Data Visualization |
| 💾 DataTables | 1.11 | Interactive Tables |
| 🎯 jQuery | 3.6 | JavaScript Library |
| 📅 DatePicker | Latest | Date Selection |
| 🔍 Select2 | 4.1 | Enhanced Select Boxes |

---

## 📁 Struktur Proyek

```
Spaneng-CI3/
├── 📂 application/                # CodeIgniter Application
│   ├── 📂 controllers/            # MVC Controllers
│   │   ├── Auth.php               # Authentication
│   │   ├── Admin.php              # Admin dashboard
│   │   ├── Kegiatan.php           # Kegiatan management
│   │   ├── Penilaian.php          # Penilaian system
│   │   ├── Persuratan.php         # LPD management
│   │   ├── Ranking.php            # Ranking system
│   │   ├── Rekap.php              # Reports & recap
│   │   ├── Master.php             # Master data
│   │   ├── User.php               # User management
│   │   └── ...
│   │
│   ├── 📂 models/                 # MVC Models
│   │   ├── Auth_model.php         # Authentication logic
│   │   ├── Kegiatan_model.php     # Kegiatan data access
│   │   ├── Penilaian_model.php    # Penilaian data access
│   │   ├── Mitra_model.php        # Mitra data access
│   │   └── ...
│   │
│   ├── 📂 views/                  # MVC Views
│   │   ├── 📂 auth/               # Login & auth pages
│   │   ├── 📂 admin/              # Admin dashboard
│   │   ├── 📂 kegiatan/           # Kegiatan views
│   │   ├── 📂 penilaian/          # Penilaian views
│   │   ├── 📂 persuratan/         # LPD views
│   │   ├── 📂 ranking/            # Ranking views
│   │   ├── 📂 rekap/              # Report views
│   │   ├── 📂 template/           # Layout templates
│   │   └── ...
│   │
│   ├── 📂 config/                 # Configuration files
│   │   ├── config.php             # App configuration
│   │   ├── database.php           # Database config
│   │   ├── routes.php             # URL routing
│   │   └── autoload.php           # Autoload config
│   │
│   ├── 📂 libraries/              # Custom libraries
│   ├── 📂 helpers/                # Custom helpers
│   └── 📂 logs/                   # Application logs
│
├── 📂 assets/                     # Static assets
│   ├── 📂 css/                    # Stylesheets
│   ├── 📂 js/                     # JavaScript files
│   ├── 📂 img/                    # Images
│   └── 📂 plugins/                # AdminLTE plugins
│
├── 📂 assets-new/                 # New assets (modernized)
│
├── 📂 uploads/                    # User uploads
│   ├── 📂 excel/                  # Excel imports
│   └── 📂 documents/              # Documents
│
├── 📂 system/                     # CodeIgniter core
├── 📂 vendor/                     # Composer dependencies
│
├── 📄 .htaccess                   # Apache rewrite rules
├── 📄 index.php                   # Application entry point
├── 📄 composer.lock               # Composer lock file
└── 📄 README.md                   # This file
```

> **⚠️ Catatan:** File `.sql` (database dumps) tidak disertakan dalam repository karena berisi data sensitif.

---

## 🚀 Instalasi

### **Prerequisites**

Pastikan sistem Anda sudah terinstall:
- 🐘 **PHP 7.4+** dengan extensions:
  - `mysqli` - Database connectivity
  - `mbstring` - String handling
  - `openssl` - Encryption
  - `zip` - Archive handling
  - `gd` - Image processing
- 🗄️ **MySQL 5.7+** atau MariaDB
- 🌐 **Apache/Nginx** web server
- 📦 **Composer** (optional, untuk dependencies)

### **1️⃣ Clone Repository**

```bash
git clone https://github.com/pandu2406/bps1504-spaneng.git
cd bps1504-spaneng
```

### **2️⃣ Setup Database**

```bash
# 1. Buat database baru
mysql -u root -p
CREATE DATABASE spaneng_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# 2. Import database schema
# Karena file .sql tidak disertakan, Anda perlu membuat struktur database sendiri
# atau meminta database dump dari administrator
```

**Struktur Database Utama:**
- `users` - Data pengguna sistem
- `mitra` - Data mitra BPS
- `pegawai` - Data pegawai BPS
- `kegiatan` - Data kegiatan survei/sensus
- `penilaian` - Data penilaian mitra
- `lpd_pegawai` - Laporan Perjalanan Dinas pegawai
- `lpd_mitra` - Laporan Perjalanan Dinas mitra
- `notifications` - Notifikasi sistem

### **3️⃣ Konfigurasi Aplikasi**

#### **Database Configuration**

Edit `application/config/database.php`:

```php
$db['default'] = array(
    'dsn'   => '',
    'hostname' => 'localhost',
    'username' => 'root',           // Sesuaikan dengan user MySQL Anda
    'password' => '',               // Sesuaikan dengan password MySQL Anda
    'database' => 'spaneng_db',     // Nama database yang dibuat
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_unicode_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
```

#### **Base URL Configuration**

Edit `application/config/config.php`:

```php
// Base URL akan auto-detect, atau set manual:
$config['base_url'] = 'http://localhost/bps1504-spaneng/';
```

#### **Email Configuration** (Optional)

Edit `application/config/email.php` untuk notifikasi email:

```php
$config['protocol'] = 'smtp';
$config['smtp_host'] = 'smtp.gmail.com';
$config['smtp_user'] = 'your-email@gmail.com';
$config['smtp_pass'] = 'your-app-password';
$config['smtp_port'] = 587;
$config['smtp_crypto'] = 'tls';
```

### **4️⃣ Set Permissions**

```bash
# Set writable permissions untuk folder yang perlu write access
chmod -R 777 application/logs
chmod -R 777 application/cache
chmod -R 777 application/sessions
chmod -R 777 uploads
```

### **5️⃣ Install Dependencies** (Optional)

Jika menggunakan Composer:

```bash
composer install
```

---

## 🎮 Cara Menjalankan

### **Development Server (PHP Built-in)**

```bash
# Dari root directory
php -S localhost:8000
```

Akses aplikasi di: **http://localhost:8000**

### **Apache/Nginx**

1. Copy folder project ke `htdocs` (XAMPP) atau `www` (WAMP)
2. Akses via browser: **http://localhost/bps1504-spaneng**

### **Login Default**

```
Username: admin
Password: admin123
```

> **⚠️ PENTING:** Segera ubah password default setelah login pertama kali!

---

## 💡 Panduan Penggunaan

### **1. Login ke Sistem**
1. Akses URL aplikasi di browser
2. Masukkan username dan password
3. Klik "Login"

### **2. Dashboard Admin**
- Lihat overview statistik kegiatan, mitra, dan penilaian
- Akses quick actions untuk fitur-fitur utama
- Monitor notifikasi terbaru

### **3. Manajemen Kegiatan**
1. **Tambah Kegiatan Baru**:
   - Menu: Kegiatan → Tambah Kegiatan
   - Isi form: Nama kegiatan, periode, satuan kerja, dll
   - Klik "Simpan"

2. **Import Kegiatan dari Excel**:
   - Menu: Kegiatan → Import Excel
   - Download template Excel
   - Isi data sesuai template
   - Upload file Excel
   - Sistem akan validasi dan import data

3. **Assign Mitra ke Kegiatan**:
   - Pilih kegiatan
   - Klik "Assign Mitra"
   - Pilih mitra yang akan ditugaskan
   - Set tanggal mulai dan selesai

### **4. Penilaian Mitra**
1. **Buat Penilaian Baru**:
   - Menu: Penilaian → Tambah Penilaian
   - Pilih kegiatan dan mitra
   - Isi nilai untuk setiap kriteria (1-100)
   - Tambahkan catatan (optional)
   - Submit penilaian

2. **Approve Penilaian** (Admin):
   - Menu: Penilaian → Pending Approval
   - Review penilaian yang masuk
   - Approve atau reject dengan alasan

### **5. Ranking Mitra**
1. Menu: Ranking → Lihat Ranking
2. Filter berdasarkan:
   - Periode (bulan/tahun)
   - Jenis kegiatan
   - Satuan kerja
3. Lihat peringkat mitra
4. Export ke Excel/PDF jika diperlukan

### **6. Persuratan (LPD)**
1. **LPD Pegawai**:
   - Menu: Persuratan → LPD Pegawai
   - Tambah LPD manual atau import Excel
   - Isi data perjalanan dinas
   - Cetak LPD

2. **LPD Mitra**:
   - Menu: Persuratan → LPD Mitra
   - Proses sama dengan LPD Pegawai

### **7. Rekap & Laporan**
1. Menu: Rekap → Pilih jenis rekap
2. Set filter periode dan kriteria
3. Lihat data rekap
4. Export ke Excel/PDF

---

## 📊 Dokumentasi API

### **Authentication Endpoints**

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `POST` | `/auth/login` | Login user |
| `GET` | `/auth/logout` | Logout user |
| `POST` | `/auth/change_password` | Ubah password |

### **Kegiatan Endpoints**

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `GET` | `/kegiatan` | List semua kegiatan |
| `GET` | `/kegiatan/view/{id}` | Detail kegiatan |
| `POST` | `/kegiatan/create` | Tambah kegiatan baru |
| `POST` | `/kegiatan/update/{id}` | Update kegiatan |
| `POST` | `/kegiatan/delete/{id}` | Hapus kegiatan |
| `POST` | `/kegiatan/import_excel` | Import dari Excel |
| `GET` | `/kegiatan/export_excel` | Export ke Excel |

### **Penilaian Endpoints**

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `GET` | `/penilaian` | List semua penilaian |
| `POST` | `/penilaian/create` | Tambah penilaian |
| `POST` | `/penilaian/approve/{id}` | Approve penilaian |
| `POST` | `/penilaian/reject/{id}` | Reject penilaian |
| `GET` | `/penilaian/history/{mitra_id}` | History penilaian mitra |

### **Ranking Endpoints**

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `GET` | `/ranking` | Lihat ranking |
| `GET` | `/ranking/filter` | Ranking dengan filter |
| `GET` | `/ranking/export` | Export ranking |

---

## 🔧 Konfigurasi Lanjutan

### **Email Notifications**

Untuk mengaktifkan email notifications, edit `application/config/email.php` dan set:

```php
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
```

### **Session Configuration**

Edit `application/config/config.php`:

```php
$config['sess_driver'] = 'files';
$config['sess_save_path'] = APPPATH . 'sessions/';
$config['sess_expiration'] = 7200; // 2 hours
```

### **Upload Configuration**

Edit `application/config/config.php` untuk set max upload size:

```php
// Max file size untuk Excel import (dalam KB)
$config['max_upload_size'] = 10240; // 10 MB
```

---

## 🐛 Troubleshooting

### **Blank Page / Error 500**
- ✅ Cek `application/logs/` untuk error details
- ✅ Pastikan PHP error reporting enabled di development
- ✅ Cek file permissions (logs, cache, sessions harus writable)

### **Database Connection Error**
- ✅ Verifikasi credentials di `application/config/database.php`
- ✅ Pastikan MySQL service running
- ✅ Cek firewall tidak block MySQL port (3306)

### **Login Tidak Berfungsi**
- ✅ Cek session folder writable: `application/sessions/`
- ✅ Verifikasi CSRF protection settings
- ✅ Clear browser cookies dan cache

### **Excel Import Gagal**
- ✅ Pastikan format Excel sesuai template
- ✅ Cek max upload size di `php.ini`: `upload_max_filesize` dan `post_max_size`
- ✅ Verifikasi PHPExcel library terinstall

### **Email Tidak Terkirim**
- ✅ Cek konfigurasi SMTP di `application/config/email.php`
- ✅ Pastikan firewall tidak block SMTP port (587/465)
- ✅ Untuk Gmail, gunakan App Password bukan password biasa

---

## 🎨 Customization

### **Mengubah Logo**

Replace file di `assets/img/logo.png` dengan logo Anda.

### **Mengubah Tema Warna**

Edit `assets/css/custom.css` atau `assets-new/css/custom.css`:

```css
:root {
    --primary-color: #3c8dbc;
    --secondary-color: #f39c12;
    --success-color: #00a65a;
    --danger-color: #dd4b39;
}
```

### **Menambah Menu**

Edit `application/views/template/sidebar.php` untuk menambah menu item.

---

## 📄 Lisensi

Distributed under the MIT License. See `LICENSE` for more information.

---

## 👨‍💻 Developer

**Pandu**
- GitHub: [@pandu2406](https://github.com/pandu2406)
- Repository: [bps1504-spaneng](https://github.com/pandu2406/bps1504-spaneng)

---

## 🙏 Acknowledgments

- **Badan Pusat Statistik (BPS)** - Kabupaten Batang Hari
- **CodeIgniter Framework** - PHP MVC Framework
- **AdminLTE** - Admin Dashboard Template
- **Bootstrap** - CSS Framework
- Semua kontributor dan pengguna aplikasi

---

## 📞 Support

Jika menemukan bug atau memiliki pertanyaan:

1. **GitHub Issues**: [Create an issue](https://github.com/pandu2406/bps1504-spaneng/issues)
2. **Email**: Hubungi administrator sistem
3. **Documentation**: Lihat [Wiki](https://github.com/pandu2406/bps1504-spaneng/wiki) (jika tersedia)

---

## 🔄 Changelog

### Version 1.0.0 (Current)
- ✅ Initial release
- ✅ Core features: Kegiatan, Penilaian, Ranking, Persuratan
- ✅ AdminLTE 3.2 integration
- ✅ Excel import/export functionality
- ✅ Email notifications
- ✅ Multi-user role management

---

<div align="center">

**⭐ Jika project ini bermanfaat, berikan star di GitHub! ⭐**

Made with ❤️ for BPS Kabupaten Batang Hari

**SPANENG** - Sistem Penilaian dan Evaluasi Beban Kerja Mitra

</div>
