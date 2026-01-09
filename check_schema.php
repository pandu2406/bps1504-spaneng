<?php
$mysqli = new mysqli("localhost", "root", "", "spaneng_test");
if ($mysqli->connect_errno)
    die("Connect failed: " . $mysqli->connect_error);

echo "--- Table: mitra ---\n";
$res = $mysqli->query("SHOW CREATE TABLE mitra");
if ($res) {
    $row = $res->fetch_row();
    echo $row[1] . "\n";
} else {
    echo "Table 'mitra' does not exist or error: " . $mysqli->error . "\n";
}

echo "\n--- Sample Data ---\n";
$res = $mysqli->query("SELECT * FROM mitra LIMIT 1");
if ($res) {
    print_r($res->fetch_assoc());
}
