<?php
/**
 * Quick Restore - Tambah Sensus Baru
 * 
 * Karena data sensus terhapus, script ini memudahkan untuk menambahkan kembali
 * 
 * CARA MENGGUNAKAN:
 * 1. Edit data sensus di bawah sesuai kebutuhan
 * 2. Jalankan: php quick_add_sensus.php
 */

// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'spaneng';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "===========================================\n";
echo "   Quick Add Sensus\n";
echo "===========================================\n\n";

// Data sensus yang akan ditambahkan
// Edit sesuai kebutuhan
$sensus_data = [
    [
        'nama' => 'Sensus Pertanian 2023',
        'start' => '2023-05-01',  // Format: YYYY-MM-DD
        'finish' => '2023-12-31',
        'k_pengawas' => 5,
        'k_pencacah' => 20,
        'seksi_id' => 1,
        'posisi' => 1,
        'satuan' => 1,
        'honor' => 500000,
        'ob' => 100000,
        'periodisitas' => 'Tahunan'
    ],
    // Tambahkan sensus lain jika perlu
    /*
    [
        'nama' => 'Sensus Ekonomi 2024',
        'start' => '2024-01-01',
        'finish' => '2024-06-30',
        'k_pengawas' => 3,
        'k_pencacah' => 15,
        'seksi_id' => 2,
        'posisi' => 1,
        'satuan' => 1,
        'honor' => 600000,
        'ob' => 150000,
        'periodisitas' => 'Semesteran'
    ],
    */
];

echo "Data sensus yang akan ditambahkan:\n\n";

foreach ($sensus_data as $index => $data) {
    echo ($index + 1) . ". " . $data['nama'] . "\n";
    echo "   Periode: " . $data['start'] . " s/d " . $data['finish'] . "\n";
    echo "   Kuota: " . $data['k_pengawas'] . " pengawas, " . $data['k_pencacah'] . " pencacah\n\n";
}

echo "Lanjutkan menambahkan data? (y/n): ";
$confirm = trim(fgets(STDIN));

if (strtolower($confirm) !== 'y') {
    die("Dibatalkan.\n");
}

$success = 0;
$failed = 0;

foreach ($sensus_data as $data) {
    // Convert date to timestamp
    $start_timestamp = strtotime($data['start']);
    $finish_timestamp = strtotime($data['finish']);

    $sql = "INSERT INTO kegiatan (nama, start, finish, k_pengawas, k_pencacah, jenis_kegiatan, seksi_id, posisi, satuan, honor, ob, periodisitas) 
            VALUES (?, ?, ?, ?, ?, 2, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "siiiiiiiiss",
        $data['nama'],
        $start_timestamp,
        $finish_timestamp,
        $data['k_pengawas'],
        $data['k_pencacah'],
        $data['seksi_id'],
        $data['posisi'],
        $data['satuan'],
        $data['honor'],
        $data['ob'],
        $data['periodisitas']
    );

    if ($stmt->execute()) {
        echo "✓ Berhasil menambahkan: " . $data['nama'] . "\n";
        $success++;

        // Sync dengan master_kegiatan
        $nama_kegiatan = $data['nama'];
        $periodisitas = $data['periodisitas'];

        $check_sql = "SELECT id FROM master_kegiatan WHERE nama = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $nama_kegiatan);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows == 0) {
            $insert_master = "INSERT INTO master_kegiatan (nama, periodisitas, jenis_kegiatan) VALUES (?, ?, 2)";
            $master_stmt = $conn->prepare($insert_master);
            $master_stmt->bind_param("ss", $nama_kegiatan, $periodisitas);
            $master_stmt->execute();
            echo "  ✓ Ditambahkan ke master_kegiatan\n";
        }
    } else {
        echo "✗ Gagal menambahkan: " . $data['nama'] . "\n";
        echo "  Error: " . $stmt->error . "\n";
        $failed++;
    }
}

echo "\n===========================================\n";
echo "Selesai!\n";
echo "Berhasil: $success\n";
echo "Gagal: $failed\n";
echo "===========================================\n\n";

echo "Silakan cek di: http://localhost:8000/kegiatan/sensus\n\n";

$conn->close();
