<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "spaneng";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE TABLE IF NOT EXISTS master_kegiatan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) UNIQUE,
    periodisitas VARCHAR(50),
    jenis_kegiatan INT
)";

if ($conn->query($sql) === TRUE) {
    echo "Table master_kegiatan created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>