<?php
// Debug script to inspect file format
$handle = fopen('u927936405_spaneng.sql', "r");
$count = 0;
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        if (strpos($line, "INSERT INTO `all_penilaian`") !== false) {
            echo "Line found: " . substr($line, 0, 200) . "...\n"; // Print first 200 chars
            $count++;
            if ($count >= 3)
                break;
        }
    }
    fclose($handle);
}
?>