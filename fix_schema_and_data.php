<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "spaneng_test";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "--- Describe 'mitra_tahun' ---\n";
$result = $conn->query("DESCRIBE mitra_tahun");
while ($row = $result->fetch_assoc()) {
    print_r($row);
}

echo "--- Show Create Table 'mitra_tahun' ---\n";
$result = $conn->query("SHOW CREATE TABLE mitra_tahun");
if ($row = $result->fetch_assoc()) {
    echo "Create Table: \n" . $row['Create Table'] . "\n";
}

$conn->close();
?>