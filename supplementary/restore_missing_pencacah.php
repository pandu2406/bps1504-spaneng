<?php
// Comprehensive Restore Script for ALL 116 Pencacah and Related Data

$json_data = file_get_contents('found_pencacah_37.json');
$pencacah_entries = json_decode($json_data, true);

if (!$pencacah_entries) {
    die("Error decoding JSON data. Run deep_scan_pencacah.php first.\n");
}

echo "Found " . count($pencacah_entries) . " pencacah entries to process.\n";

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'spaneng';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 1. Restore/Update all_kegiatan_pencacah
// We use INSERT IGNORE based on ID. 
// Note: kegiatan_id is 1000037 for all these.

$stmt = $conn->prepare("INSERT IGNORE INTO all_kegiatan_pencacah (id, kegiatan_id, id_pengawas, id_mitra) VALUES (?, ?, ?, ?)");
$update_stmt = $conn->prepare("UPDATE all_kegiatan_pencacah SET kegiatan_id = ?, id_pengawas = ?, id_mitra = ? WHERE id = ?");

$restored_count = 0;
$updated_count = 0;

foreach ($pencacah_entries as $row) {
    $id = $row['id'];
    $kegiatan_id = 1000037; // New ID
    $id_pengawas = $row['id_pengawas'];
    $id_mitra = $row['id_mitra'];

    // Check if exists
    $check = $conn->query("SELECT id, kegiatan_id FROM all_kegiatan_pencacah WHERE id = $id");

    if ($check->num_rows == 0) {
        // Insert
        $stmt->bind_param("iiii", $id, $kegiatan_id, $id_pengawas, $id_mitra);
        if ($stmt->execute()) {
            $restored_count++;
        } else {
            echo "Error inserting ID $id: " . $stmt->error . "\n";
        }
    } else {
        // Exists. Check if it points to correct kegiatan_id?
        $curr = $check->fetch_assoc();
        if ($curr['kegiatan_id'] != $kegiatan_id) {
            // Update
            $update_stmt->bind_param("iiii", $kegiatan_id, $id_pengawas, $id_mitra, $id);
            $update_stmt->execute();
            $updated_count++;
        }
    }
}

echo "Restored: $restored_count new entries.\n";
echo "Updated: $updated_count existing entries.\n";


// 2. Restore all_penilaian
// Scan SQL file for ALL LINKED penilaian
echo "Scanning for all_penilaian...\n";
$handle = fopen('u927936405_spaneng.sql', "r");
$pencacah_ids = array_column($pencacah_entries, 'id');
$penilaian_count = 0;

if ($handle) {
    $stmt_pen = $conn->prepare("INSERT IGNORE INTO all_penilaian (id, all_kegiatan_pencacah_id, kriteria_id, nilai, t_bobot) VALUES (?, ?, ?, ?, ?)");

    while (($line = fgets($handle)) !== false) {
        // Parse Insert Value Lines
        // Matches: (id, penc_id, krit_id, val, weight)
        // Improved Regex: Allow optional spaces around commas and numbers
        if (preg_match_all('/\(\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*([0-9.]+)\s*,\s*([0-9.]+)\s*\)/', $line, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $p_id = $m[2]; // all_kegiatan_pencacah_id
                if (in_array($p_id, $pencacah_ids)) {
                    $stmt_pen->bind_param("iiiid", $m[1], $m[2], $m[3], $m[4], $m[5]);
                    if ($stmt_pen->execute()) {
                        if ($stmt_pen->affected_rows > 0) {
                            $penilaian_count++;
                        }
                    }
                }
            }
        }
    }
    fclose($handle);
}

echo "Restored $penilaian_count entries in all_penilaian.\n";
?>