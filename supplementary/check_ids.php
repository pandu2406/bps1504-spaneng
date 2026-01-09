<?php
// Script to check max IDs
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'spaneng'; // Correct local DB name

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$tables = ['all_kegiatan_pencacah', 'all_kegiatan_pengawas', 'all_penilaian'];

foreach ($tables as $table) {
    $result = $conn->query("SELECT MAX(id) as max_id FROM $table");
    $row = $result->fetch_assoc();
    echo "Max ID for $table: " . ($row['max_id'] ?? 0) . "\n";
}

// Check if IDs 172-250 are free in all_kegiatan_pencacah
// The backup data has IDs in this range
$result = $conn->query("SELECT id FROM all_kegiatan_pencacah WHERE id BETWEEN 172 AND 250");
if ($result->num_rows > 0) {
    echo "WARNING: IDs 172-250 are NOT completely free in all_kegiatan_pencacah. Found entries: ";
    while ($row = $result->fetch_assoc()) {
        echo $row['id'] . ", ";
    }
    echo "\n";
} else {
    echo "IDs 172-250 are free in all_kegiatan_pencacah.\n";
}
