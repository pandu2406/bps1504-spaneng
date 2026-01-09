<?php
/**
 * Script untuk mengembalikan data Sensus yang terhapus
 * 
 * CARA MENGGUNAKAN:
 * php restore_sensus.php
 */

// Load CodeIgniter
define('BASEPATH', TRUE);
require_once 'index.php';

// Atau gunakan direct database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'spaneng';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "===========================================\n";
echo "   Restore Sensus Data\n";
echo "===========================================\n\n";

// Check apakah ada data sensus yang terhapus (soft delete)
$sql = "SELECT id, nama, start, finish, deleted_at FROM kegiatan WHERE jenis_kegiatan = 2 ORDER BY id DESC LIMIT 20";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "Data Sensus yang ditemukan:\n\n";
    while ($row = $result->fetch_assoc()) {
        $status = isset($row['deleted_at']) && $row['deleted_at'] ? " [DELETED]" : "";
        echo "ID: " . $row['id'] . " - " . $row['nama'] . $status . "\n";
        echo "   Start: " . date('Y-m-d', $row['start']) . "\n";
        echo "   Finish: " . date('Y-m-d', $row['finish']) . "\n\n";
    }
} else {
    echo "Tidak ada data sensus ditemukan.\n";
    echo "Kemungkinan data sudah benar-benar terhapus (hard delete).\n\n";
    echo "Untuk restore, Anda perlu:\n";
    echo "1. Restore dari backup database\n";
    echo "2. Atau input ulang data sensus\n\n";
}

// Jika ingin restore (uncomment jika perlu)
/*
echo "Masukkan ID sensus yang ingin di-restore: ";
$id = trim(fgets(STDIN));

if (!empty($id)) {
    $sql = "UPDATE kegiatan SET deleted_at = NULL WHERE id = ? AND jenis_kegiatan = 2";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "✓ Sensus ID $id berhasil di-restore!\n";
    } else {
        echo "✗ Gagal restore: " . $conn->error . "\n";
    }
}
*/

$conn->close();
