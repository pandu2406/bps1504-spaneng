<?php
$mysqli = new mysqli("localhost", "root", "", "spaneng");

echo "<h3>Mitra Table</h3>";
$res = $mysqli->query("DESCRIBE mitra");
while ($row = $res->fetch_assoc()) {
    print_r($row);
    echo "<br>";
}

echo "<h3>Mitra Indexes</h3>";
$res = $mysqli->query("SHOW INDEX FROM mitra");
while ($row = $res->fetch_assoc()) {
    print_r($row);
    echo "<br>";
}

echo "<h3>Mitra Tahun Table</h3>";
$res = $mysqli->query("DESCRIBE mitra_tahun");
while ($row = $res->fetch_assoc()) {
    print_r($row);
    echo "<br>";
}
