<?php
// Script to run SQL migration
$host = 'localhost';
$user = 'root';
$pass = '';
$db_name = 'spaneng_test';

$mysqli = new mysqli($host, $user, $pass, $db_name);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Read SQL file
$sql = file_get_contents('006_add_notification_column.sql');

// Split by line to run individually or just run multi_query
if ($mysqli->multi_query($sql)) {
    do {
        /* store first result set */
        if ($result = $mysqli->store_result()) {
            $result->free();
        }
    } while ($mysqli->next_result());
    echo "Migration 006 executed successfully.\n";
} else {
    echo "Error executing migration: " . $mysqli->error . "\n";
}

$mysqli->close();
?>