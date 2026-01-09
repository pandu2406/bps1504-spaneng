<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db_name = 'spaneng_test';

$mysqli = new mysqli($host, $user, $pass, $db_name);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$result = $mysqli->query("SHOW CREATE VIEW kegiatan");
$row = $result->fetch_array();
echo $row[1];

$mysqli->close();
?>