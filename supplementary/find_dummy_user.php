<?php
define('BASEPATH', 'e:/Ngoding/spaneng/');
define('ENVIRONMENT', 'development');
require_once('application/config/database.php');

$db_config = $db['default'];
$conn = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name_query = "dummy delete";
$sql = "SELECT id, nama FROM kegiatan WHERE nama LIKE '%" . $conn->real_escape_string($name_query) . "%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Found ID: " . $row['id'] . " - " . $row['nama'] . "\n";
    }
} else {
    echo "No survey found with name query '$name_query'\n";
}
?>