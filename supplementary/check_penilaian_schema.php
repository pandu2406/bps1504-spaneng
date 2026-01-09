<?php
$mysqli = new mysqli("localhost", "root", "", "spaneng");

echo "Tables containing 'nilai':\n";
$r = $mysqli->query("SHOW TABLES LIKE '%nilai%'");
while ($row = $r->fetch_row()) {
    echo $row[0] . "\n";
    $r2 = $mysqli->query("DESCRIBE " . $row[0]);
    while ($row2 = $r2->fetch_assoc()) {
        echo " - " . $row2['Field'] . " (" . $row2['Type'] . ")\n";
    }
    echo "\n";
}

echo "Mitra Columns:\n";
$r = $mysqli->query("DESCRIBE mitra");
while ($row = $r->fetch_assoc()) {
    echo " - " . $row['Field'] . "\n";
}
