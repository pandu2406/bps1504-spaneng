<?php
/**
 * SSO Client Secret Setup Helper
 * 
 * Script ini memudahkan setup client secret untuk SSO tanpa perlu edit file config
 * 
 * CARA MENGGUNAKAN:
 * 1. Dapatkan Client Secret dari admin SSO BPS
 * 2. Jalankan script ini: php setup_sso_secret.php
 * 3. Masukkan Client Secret saat diminta
 * 4. Script akan otomatis update file config/sso.php
 */

// Pastikan dijalankan dari command line
if (php_sapi_name() !== 'cli') {
    die("Script ini hanya bisa dijalankan dari command line\n");
}

echo "===========================================\n";
echo "   SSO Client Secret Setup Helper\n";
echo "===========================================\n\n";

// Path ke file config
$config_file = __DIR__ . '/application/config/sso.php';

// Check apakah file config ada
if (!file_exists($config_file)) {
    die("ERROR: File config/sso.php tidak ditemukan!\n");
}

echo "File config ditemukan: $config_file\n\n";

// Baca file config
$config_content = file_get_contents($config_file);

// Tampilkan status current
if (strpos($config_content, 'YOUR_CLIENT_SECRET_HERE') !== false) {
    echo "Status: Client Secret belum dikonfigurasi\n";
    echo "Current: YOUR_CLIENT_SECRET_HERE (placeholder)\n\n";
} else {
    echo "Status: Client Secret sudah dikonfigurasi\n";
    echo "Anda bisa mengupdate dengan secret yang baru\n\n";
}

// Minta input client secret
echo "Masukkan Client Secret untuk SSO Mitra:\n";
echo "(Dapatkan dari admin SSO BPS atau SSO Admin Panel)\n";
echo "Client Secret: ";

$client_secret = trim(fgets(STDIN));

// Validasi input
if (empty($client_secret)) {
    die("\nERROR: Client Secret tidak boleh kosong!\n");
}

if ($client_secret === 'YOUR_CLIENT_SECRET_HERE') {
    die("\nERROR: Gunakan Client Secret yang sebenarnya, bukan placeholder!\n");
}

// Konfirmasi
echo "\nAnda akan mengupdate Client Secret ke:\n";
echo "$client_secret\n\n";
echo "Lanjutkan? (y/n): ";

$confirm = trim(fgets(STDIN));

if (strtolower($confirm) !== 'y') {
    die("Setup dibatalkan.\n");
}

// Backup file config
$backup_file = $config_file . '.backup.' . date('YmdHis');
if (!copy($config_file, $backup_file)) {
    die("\nERROR: Gagal membuat backup file!\n");
}

echo "\nBackup dibuat: " . basename($backup_file) . "\n";

// Update config
$config_content = preg_replace(
    "/'client_secret'\s*=>\s*'[^']*'/",
    "'client_secret' => '$client_secret'",
    $config_content
);

// Simpan file
if (file_put_contents($config_file, $config_content) === false) {
    die("\nERROR: Gagal menyimpan file config!\n");
}

echo "✓ Client Secret berhasil diupdate!\n\n";

// Tampilkan next steps
echo "===========================================\n";
echo "   Setup Selesai!\n";
echo "===========================================\n\n";

echo "Next Steps:\n";
echo "1. Jalankan database migration (db_migration_sso.sql)\n";
echo "2. Test SSO login di http://localhost:8000/auth\n";
echo "3. Klik tombol 'Login dengan SSO Mitra'\n";
echo "4. Login dengan credential Mitra BPS\n\n";

echo "Untuk production:\n";
echo "- Update redirect_uri di config/sso.php\n";
echo "- Daftarkan redirect_uri ke SSO provider\n";
echo "- Enable HTTPS\n\n";

echo "File backup tersimpan di:\n";
echo "$backup_file\n\n";
