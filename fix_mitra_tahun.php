<?php
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'spaneng_test';

$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error . "\n");
}

echo "Fixing missing mitra_tahun records...\n";

// Insert into mitra_tahun for 2026 based on mitra_old records for 2026
// that don't already have a mitra_tahun entry.
$sql = "
    INSERT INTO mitra_tahun (id_mitra, tahun, posisi, is_active)
    SELECT id_mitra, 2026, posisi, 1
    FROM mitra_old
    WHERE tahun = 2026
    AND id_mitra NOT IN (
        SELECT id_mitra FROM mitra_tahun WHERE tahun = 2026
    )
";

if ($mysqli->query($sql) === TRUE) {
    echo "Success! Inserted " . $mysqli->affected_rows . " rows into mitra_tahun.\n";
} else {
    echo "Error: " . $mysqli->error . "\n";
}

$mysqli->close();
