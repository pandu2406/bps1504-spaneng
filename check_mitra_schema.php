<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "spaneng_test";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "--- Describe 'mitra' table ---\n";
$result = $conn->query("DESCRIBE mitra");
while ($row = $result->fetch_assoc()) {
    print_r($row);
}
$conn->close();
?>