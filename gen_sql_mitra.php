<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$inputFile = 'assets/excel/data_mitra.xlsx';
$outputFile = 'generated_mitra_insert.sql';

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

$sql = "-- Generated SQL for Mitra Import (Year 2026)\n";
$sql .= "BEGIN TRANSACTION;\n\n";

$count = 0;
foreach ($rows as $index => $row) {
    // Skip header row
    if ($index == 0)
        continue;

    // Excel Column Mapping (0-based index)
    // A=0: NIK, B=1: Nama, C=2: Posisi, D=3: Email, E=4: Kecamatan, F=5: Desa, G=6: Alamat, H=7: JK, I=8: HP, J=9: SobatID

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

    // Sanitize for SQL
    $nik_safe = addslashes($nik);
    $nama_safe = addslashes($nama);
    $email_safe = addslashes($email);
    $kec_safe = addslashes($kecamatan);
    $desa_safe = addslashes($desa);
    $alamat_safe = addslashes($alamat);
    $hp_safe = addslashes($no_hp);
    $sobat_safe = addslashes($sobat_id);
    $posisi_safe = addslashes($posisi);

    // SQL 1: Insert/Ignore into mitra_old
    $sql .= "INSERT INTO mitra_old (nik, nama, email, kecamatan, desa, alamat, jk, no_hp, sobat_id) VALUES ('$nik_safe', '$nama_safe', '$email_safe', '$kec_safe', '$desa_safe', '$alamat_safe', $jk, '$hp_safe', '$sobat_safe') ON DUPLICATE KEY UPDATE nama='$nama_safe', email='$email_safe', kecamatan='$kec_safe', desa='$desa_safe', alamat='$alamat_safe', jk=$jk, no_hp='$hp_safe', sobat_id='$sobat_safe';\n";

    // SQL 2: Insert into mitra_tahun for 2026
    $sql .= "INSERT INTO mitra_tahun (id_mitra, tahun, posisi, is_active) SELECT id_mitra, 2026, '$posisi_safe', 1 FROM mitra_old WHERE nik = '$nik_safe' ON DUPLICATE KEY UPDATE posisi='$posisi_safe', is_active=1;\n";

    $count++;
}

$sql .= "\nCOMMIT;\n";
$sql .= "-- Total Data Processed: $count\n";

file_put_contents($outputFile, $sql);
echo "Successfully generated SQL for $count records to $outputFile\n";
