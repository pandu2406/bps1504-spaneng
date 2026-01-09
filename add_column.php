<?php
$mysqli = new mysqli("localhost", "root", "", "spaneng_test");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

// 1. Alter Table
$query1 = "ALTER TABLE kegiatan_old ADD COLUMN beban_standar INT(11) DEFAULT 1";
if ($mysqli->query($query1) === TRUE) {
    echo "Column beban_standar added to kegiatan_old.\n";
} else {
    // Ignore error if column exists, but print it
    echo "Error adding column (maybe exists): " . $mysqli->error . "\n";
}

// 2. Update View
$query2 = "CREATE OR REPLACE VIEW kegiatan AS SELECT id, nama, periodisitas, start, finish, k_pengawas, k_pencacah, jenis_kegiatan, seksi_id, ob, posisi, satuan, honor, beban_standar FROM kegiatan_old";
if ($mysqli->query($query2) === TRUE) {
    echo "View kegiatan updated.\n";
} else {
    echo "Error updating view: " . $mysqli->error . "\n";
}

$mysqli->close();
?>