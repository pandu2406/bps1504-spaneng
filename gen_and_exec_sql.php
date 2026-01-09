<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use mysqli;

// Configuration
$inputFile = 'assets/excel/data_mitra.xlsx';
$outputFile = 'generated_mitra_insert.sql';
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'spaneng_test';

// 1. Load Excel
if (!file_exists($inputFile)) {
    die("File not found: $inputFile\n");
}

try {
    $spreadsheet = IOFactory::load($inputFile);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();
} catch (Exception $e) {
    die("Error loading file: " . $e->getMessage() . "\n");
}

echo "Generating SQL...\n";

// 2. Build SQL
$sqlContent = "-- Generated SQL for Mitra Import (Year 2026) -> Mitra_Old\n";
// Note: We do not enclose in TRANSACTION for the file executed via PHP loop to ensure error reporting per line if needed, 
// but for the file output it's good. However, multi_query might prefer straight inserts.
// Let's use standard inserts.

$inserts = [];
$count = 0;

foreach ($rows as $index => $row) {
    if ($index == 0)
        continue; // Skip header

    // Map columns
    $nik = trim($row[0] ?? '');
    $nama = trim($row[1] ?? '');

    if (empty($nik) && empty($nama))
        continue;

    $posisi = trim($row[2] ?? 'Mitra Pendataan');
    $email = trim($row[3] ?? '');
    $kecamatan = trim($row[4] ?? '');
    $desa = trim($row[5] ?? '');
    $alamat = trim($row[6] ?? '');
    $raw_jk = strtoupper(trim($row[7] ?? ''));
    $no_hp = trim($row[8] ?? '');
    $sobat_id = trim($row[9] ?? '');

    // Normalize JK
    $jk = 0;
    if (in_array($raw_jk, ['1', 'L', 'LAKI-LAKI', 'PRIA']))
        $jk = 1;
    elseif (in_array($raw_jk, ['2', 'P', 'PEREMPUAN', 'WANITA']))
        $jk = 2;

    // Sanitize
    $nik_safe = addslashes($nik);
    $nama_safe = addslashes($nama);
    $posisi_safe = addslashes($posisi);
    $email_safe = addslashes($email);
    $kec_safe = addslashes($kecamatan);
    $desa_safe = addslashes($desa);
    $alamat_safe = addslashes($alamat);
    $hp_safe = addslashes($no_hp);
    $sobat_safe = addslashes($sobat_id);

    // Fixed values for schema
    $tahun = 2026;
    $is_active = 1;

    // INSERT Statement
    // We assume we are appending new rows for 2026. 
    // If we need to update existing, we would need a unique key. 
    // Assuming just INSERT based on User request "add excel ... to table".
    $inserts[] = "INSERT INTO mitra_old (nik, nama, posisi, email, kecamatan, desa, alamat, jk, no_hp, sobat_id, tahun, is_active) VALUES ('$nik_safe', '$nama_safe', '$posisi_safe', '$email_safe', '$kec_safe', '$desa_safe', '$alamat_safe', $jk, '$hp_safe', '$sobat_safe', $tahun, $is_active);";

    $count++;
}

// Save to file
$fullSql = implode("\n", $inserts);
file_put_contents($outputFile, $fullSql);
echo "SQL File generated ($count rows): $outputFile\n";

// 3. Execute SQL
echo "Connecting to database '$dbName'...\n";
$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error . "\n");
}

echo "Executing queries...\n";
$success = 0;
$fail = 0;

foreach ($inserts as $query) {
    if ($mysqli->query($query) === TRUE) {
        $success++;
    } else {
        echo "Error: " . $mysqli->error . "\nOn Query: $query\n";
        $fail++;
    }
}

$mysqli->close();

echo "Done.\n";
echo "Success: $success\n";
echo "Failed: $fail\n";
