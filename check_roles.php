<?php
$mysqli = new mysqli('localhost', 'root', '', 'spaneng_test');
$result = $mysqli->query("SELECT * FROM user_role_old");
while ($row = $result->fetch_assoc()) {
    print_r($row);
}
