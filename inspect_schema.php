<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'spaneng';

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 1. Describe system_audit_log
echo "DESCRIBE system_audit_log:\n";
$result = $conn->query("DESCRIBE system_audit_log");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} else {
    echo "Error describing system_audit_log: " . $conn->error . "\n";
}

// 2. Describe kegiatan_old (to confirm existence)
echo "\nDESCRIBE kegiatan_old:\n";
$result = $conn->query("DESCRIBE kegiatan_old");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} else {
    echo "Error describing kegiatan_old: " . $conn->error . "\n";
}

$conn->close();
?>