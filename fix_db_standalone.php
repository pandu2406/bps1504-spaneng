<?php
// Fix DB Standalone for spaneng_test
header('Content-Type: text/plain');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'spaneng_test';

try {
    $mysqli = new mysqli($host, $user, $pass, $db);
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}

echo "Connected successfully to $db\n";

$tables = ['mitra', 'kegiatan', 'pegawai'];

foreach ($tables as $table) {
    echo "Processing table: $table\n";

    // Check column
    $result = $mysqli->query("SHOW COLUMNS FROM `$table` LIKE 'token'");
    if ($result->num_rows == 0) {
        echo " - Column 'token' MISSING. Adding...\n";
        try {
            // Simplified ALTER without AFTER
            $sql = "ALTER TABLE `$table` ADD COLUMN `token` VARCHAR(64) NULL";
            $mysqli->query($sql);
            echo " - Column added successfully.\n";

            // Add Index
            $mysqli->query("ALTER TABLE `$table` ADD UNIQUE INDEX `idx_token` (`token`)");
        } catch (Exception $e) {
            echo " - Error adding column: " . $e->getMessage() . "\n";
        }
    } else {
        echo " - Column 'token' ALREADY EXISTS.\n";
    }

    // Populate
    try {
        $pk = ($table == 'mitra') ? 'id_mitra' : (($table == 'pegawai') ? 'id_peg' : 'id');
        $result = $mysqli->query("SELECT $pk FROM `$table` WHERE token IS NULL OR token = ''");

        if ($result && $result->num_rows > 0) {
            echo " - Found " . $result->num_rows . " rows to update.\n";
            while ($row = $result->fetch_assoc()) {
                $id = $row[$pk];
                $token = bin2hex(random_bytes(32));
                $mysqli->query("UPDATE `$table` SET token = '$token' WHERE $pk = '$id'");
            }
            echo " - Updated rows.\n";
        } else {
            echo " - No rows to update.\n";
        }
    } catch (Exception $e) {
        echo " - Error updating rows: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

echo "Done.";
$mysqli->close();
