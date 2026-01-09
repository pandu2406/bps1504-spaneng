<?php
$mysqli = new mysqli("localhost", "root", "", "spaneng_test");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}
$res = $mysqli->query("SHOW COLUMNS FROM mitra");
echo "Columns:\n";
while ($row = $res->fetch_assoc()) {
    echo $row['Field'] . "\n";
}
?>