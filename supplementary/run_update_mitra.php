<?php
$mysqli = new mysqli("localhost", "root", "", "spaneng");
if ($mysqli->connect_errno) {
    die("Conn failed");
}

$sql = file_get_contents(__DIR__ . '/update_mitra_schema.sql');
$queries = explode(';', $sql);

foreach ($queries as $query) {
    $query = trim($query);
    if (!empty($query)) {
        if ($mysqli->query($query)) {
            echo "Success: " . substr($query, 0, 30) . "...\n";
        } else {
            echo "Error: " . $mysqli->error . "\nQuery: " . $query . "\n";
        }
    }
}
$mysqli->close();
