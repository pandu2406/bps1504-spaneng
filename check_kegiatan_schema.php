<?php
$mysqli = new mysqli("localhost", "root", "", "spaneng_test");
if ($mysqli->connect_errno)
    die("Connect failed: " . $mysqli->connect_error);

echo "--- Table: kegiatan ---\n";
$res = $mysqli->query("SHOW COLUMNS FROM kegiatan");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo $row['Field'] . "\n";
    }
} else {
    echo "Error: " . $mysqli->error . "\n";
}
