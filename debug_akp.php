<?php
$mysqli = new mysqli("localhost", "root", "", "spaneng_test");
$id = 1000040;
$res = $mysqli->query("SELECT * FROM all_kegiatan_pencacah WHERE kegiatan_id = $id");
echo "Rows in all_kegiatan_pencacah for ID $id:\n";
while ($row = $res->fetch_assoc()) {
    echo json_encode($row) . "\n";
}
?>