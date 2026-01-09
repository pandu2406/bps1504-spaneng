<?php
// Script to verify restore status
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'spaneng';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 1. Count Pencacah for Kegiatan 1000037
$kegiatan_id = 1000037;
$res = $conn->query("SELECT id FROM all_kegiatan_pencacah WHERE kegiatan_id = $kegiatan_id");
$pencacah_ids = [];
while ($row = $res->fetch_assoc()) {
    $pencacah_ids[] = $row['id'];
}
$count_pencacah = count($pencacah_ids);
echo "Total Pencacah for Kegiatan ID $kegiatan_id: $count_pencacah (Expected: 116)\n";

// 2. Count Penilaian for these Pencacah
if ($count_pencacah > 0) {
    $ids_str = implode(',', $pencacah_ids);
    $res2 = $conn->query("SELECT COUNT(*) as total FROM all_penilaian WHERE all_kegiatan_pencacah_id IN ($ids_str)");
    $row2 = $res2->fetch_assoc();
    $count_penilaian = $row2['total'];
    echo "Total Penilaian Linked: $count_penilaian\n";
    echo "Average Penilaian per Pencacah: " . ($count_penilaian / $count_pencacah) . "\n";
} else {
    echo "No pencacah found.\n";
}

// 3. Compare with SQL Backup (Count expected)
// We will scan the file u927936405_spaneng.sql again to see how many INSERT lines exist for all_penilaian 
// that refer to these IDs.

$handle = fopen('u927936405_spaneng.sql', "r");
$expected_penilaian = 0;
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        // Regex to find values in all_penilaian table
        // Format: (id, all_kegiatan_pencacah_id, ...)
        // We match all occurrences.
        if (preg_match_all('/\([0-9]+, ([0-9]+), [0-9]+, [0-9.]+, [0-9.]+\)/', $line, $matches)) {
            // $matches[1] contains the all_kegiatan_pencacah_id
            foreach ($matches[1] as $pid) {
                if (in_array($pid, $pencacah_ids)) {
                    $expected_penilaian++;
                }
            }
        }
    }
    fclose($handle);
}

echo "Expected Penilaian from Backup: $expected_penilaian\n";

if ($count_penilaian == $expected_penilaian) {
    echo "STATUS: MATCHED. All available assessments have been restored.\n";
} else {
    echo "STATUS: MISMATCH. Missing " . ($expected_penilaian - $count_penilaian) . " records.\n";
}
?>