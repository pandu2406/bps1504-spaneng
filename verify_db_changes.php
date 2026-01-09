<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'spaneng';

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 1. Drop unused table
$sql_drop = "DROP TABLE IF EXISTS kegiatan_sensus_backup";
if ($conn->query($sql_drop) === TRUE) {
    echo "Dropped table kegiatan_sensus_backup\n";
} else {
    echo "Error dropping table: " . $conn->error . "\n";
}

// 2. Check traffic_log
$result = $conn->query("SELECT count(*) as count FROM traffic_log");
if ($result) {
    $row = $result->fetch_assoc();
    echo "Traffic Log Entries: " . $row['count'] . "\n";
} else {
    echo "Error reading traffic_log: " . $conn->error . "\n";
}

$conn->close();
?>