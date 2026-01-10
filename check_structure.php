<?php
$mysqli = new mysqli('localhost', 'root', '', 'spaneng_test');
$result = $mysqli->query("SHOW FULL TABLES WHERE Tables_in_spaneng_test LIKE 'user%'");
while ($row = $result->fetch_row()) {
    echo "Table: " . $row[0] . " | Type: " . $row[1] . "\n";
}
