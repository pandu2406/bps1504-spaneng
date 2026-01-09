<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "spaneng_test";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "--- Show Create Table 'mitra' ---\n";
$result = $conn->query("SHOW CREATE TABLE mitra");
if ($row = $result->fetch_assoc()) {
    echo $row['Create Table'];
}
$conn->close();
?>