<?php
$mysqli = new mysqli('localhost', 'root', '', 'spaneng_test');
$sql = "UPDATE user_role_old SET role = 'Penanggung Jawab Kegiatan (PJK)' WHERE id = 3";
if ($mysqli->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $mysqli->error;
}
