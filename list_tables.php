<?php
// Load CodeIgniter's database configuration manually or use a simple mysqli connection
// Since we are in the root, it's easier to just use standard PHP MySQLi if we know the creds, 
// OR try to bootstrap CI. Simple MySQLi is safer given the environment.

$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'spaneng';

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SHOW TABLES";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "TABLES_FOUND:\n";
    while ($row = $result->fetch_array()) {
        echo $row[0] . "\n";
    }
} else {
    echo "0 results";
}
$conn->close();
?>