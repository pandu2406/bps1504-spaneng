<?php
// Script to restore allocation data for kegiatan ID 37 (now 1000037)
// This script extracts data from the SQL backup and inserts it into the database

define('BASEPATH', 'e:/Ngoding/spaneng/'); // Dummy to prevent script access denial if any
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'spaneng';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Starting restore process...\n";

// 1. Restore all_kegiatan_pencacah
// Data extracted from u927936405_spaneng.sql lines ~200-250
// Format: (id, kegiatan_id, id_pengawas, id_mitra)
// We replace kegiatan_id 37 with 1000037
$pencacah_data = [
    [172, 1000037, 60, 143],
    [173, 1000037, 60, 159],
    [174, 1000037, 60, 213],
    [175, 1000037, 60, 67],
    [176, 1000037, 60, 20],
    [177, 1000037, 60, 148],
    [178, 1000037, 340054651, 157],
    [179, 1000037, 340054651, 154],
    [180, 1000037, 340054651, 153],
    [181, 1000037, 340054651, 151],
    [182, 1000037, 340060064, 235],
    [183, 1000037, 340054651, 21],
    [184, 1000037, 340054651, 152],
    [185, 1000037, 340060064, 138],
    [186, 1000037, 340060064, 144],
    [187, 1000037, 340060064, 140],
    [188, 1000037, 340060064, 32],
    [189, 1000037, 340060064, 150],
    [190, 1000037, 340017146, 118],
    [191, 1000037, 340017146, 202],
    [192, 1000037, 340017146, 129],
    [193, 1000037, 340017146, 198],
    [194, 1000037, 64, 128],
    [195, 1000037, 64, 204],
    [196, 1000037, 64, 59],
    [197, 1000037, 64, 141],
    [198, 1000037, 64, 175],
    [199, 1000037, 35, 37],
    [200, 1000037, 35, 84],
    [201, 1000037, 35, 127],
    [202, 1000037, 35, 22],
    [203, 1000037, 35, 38],
    [204, 1000037, 340017522, 113],
    [205, 1000037, 340017522, 52],
    [206, 1000037, 340017522, 110],
    [207, 1000037, 340017522, 115],
    [208, 1000037, 340059527, 40],
    [209, 1000037, 340018881, 114],
    [210, 1000037, 340018881, 174],
    [211, 1000037, 340018881, 136],
    [212, 1000037, 340018881, 41],
    [213, 1000037, 340018881, 208],
    [214, 1000037, 340059527, 134],
    [215, 1000037, 340059527, 239],
    [216, 1000037, 340059527, 43],
    [217, 1000037, 340059527, 112],
    [218, 1000037, 340017522, 209],
    [219, 1000037, 340016150, 206],
    [220, 1000037, 340019347, 132],
    [221, 1000037, 340019347, 80],
    [222, 1000037, 340019347, 171]
];

echo "Restoring " . count($pencacah_data) . " rows to all_kegiatan_pencacah...\n";
$stmt = $conn->prepare("INSERT IGNORE INTO all_kegiatan_pencacah (id, kegiatan_id, id_pengawas, id_mitra) VALUES (?, ?, ?, ?)");
foreach ($pencacah_data as $row) {
    // Check if ID exists first to avoid auto-increment gaps if we rely on IGNORE
    $check = $conn->query("SELECT id FROM all_kegiatan_pencacah WHERE id = " . $row[0]);
    if ($check->num_rows == 0) {
        $stmt->bind_param("iiii", $row[0], $row[1], $row[2], $row[3]);
        if (!$stmt->execute()) {
            echo "Error inserting pencacah ID " . $row[0] . ": " . $stmt->error . "\n";
        }
    } else {
        echo "Pencacah ID " . $row[0] . " already exists. Skipped.\n";
    }
}

// 2. Restore all_kegiatan_pengawas
// Data extracted from lines 632-654
// Format: (id, kegiatan_id, id_pengawas, sumber_pengawas)
$pengawas_data = [
    [74, 1000037, 340054651, 'pegawai'],
    [75, 1000037, 340060064, 'pegawai'],
    [76, 1000037, 340017146, 'pegawai'],
    [77, 1000037, 340018881, 'pegawai'],
    [78, 1000037, 340017522, 'pegawai'],
    [79, 1000037, 340059527, 'pegawai'],
    [80, 1000037, 340019347, 'pegawai'],
    [81, 1000037, 340016150, 'pegawai'],
    [82, 1000037, 340056947, 'pegawai'],
    [83, 1000037, 340057667, 'pegawai'],
    [84, 1000037, 340060595, 'pegawai'],
    [85, 1000037, 340061959, 'pegawai'],
    [86, 1000037, 340060586, 'pegawai'],
    [87, 1000037, 340019368, 'pegawai'],
    [88, 1000037, 340061763, 'pegawai'],
    [89, 1000037, 340019333, 'pegawai'],
    [90, 1000037, 340059726, 'pegawai'],
    [91, 1000037, 58, 'pegawai'],
    [92, 1000037, 30, 'pegawai'],
    [93, 1000037, 78, 'pegawai'],
    [94, 1000037, 35, 'pegawai'],
    [95, 1000037, 64, 'pegawai'],
    [96, 1000037, 60, 'pegawai']
];

echo "Restoring " . count($pengawas_data) . " rows to all_kegiatan_pengawas...\n";
$stmt = $conn->prepare("INSERT IGNORE INTO all_kegiatan_pengawas (id, kegiatan_id, id_pengawas, sumber_pengawas) VALUES (?, ?, ?, ?)");
foreach ($pengawas_data as $row) {
    $check = $conn->query("SELECT id FROM all_kegiatan_pengawas WHERE id = " . $row[0]);
    if ($check->num_rows == 0) {
        $stmt->bind_param("iiis", $row[0], $row[1], $row[2], $row[3]);
        if (!$stmt->execute()) {
            echo "Error inserting pengawas ID " . $row[0] . ": " . $stmt->error . "\n";
        }
    } else {
        echo "Pengawas ID " . $row[0] . " already exists. Skipped.\n";
    }
}

// 3. Restore all_penilaian
// Format: (id, all_kegiatan_pencacah_id, kriteria_id, nilai, t_bobot)
// Based on SQL lines 816-835 for IDs 206, 207, 205, 204 etc. which correspond to our pencacah list above
// Wait, I need to check which of ALL IDs in pencacah_data have entries in all_penilaian
// I'll assume all entries related to these IDs in the SQL file should be restored.
// For brevity in this script, I'll extract some known blocks corresponding to the IDs 204-218 range seen in SQL view earlier
// Lines 816-840 cover IDs: 206, 207, 205, 204, 218. 
// I need to add more if available, but let's start with what we saw.
// Note: Only restoring those that match our restored pencacah IDs.

$penilaian_data = [
    // entries for 206
    [86, 206, 1, 95, 0],
    [87, 206, 2, 95, 0],
    [88, 206, 3, 95, 0],
    [89, 206, 4, 95, 0],
    [90, 206, 10, 98, 0],
    // entries for 207
    [91, 207, 1, 95, 0],
    [92, 207, 2, 95, 0],
    [93, 207, 3, 95, 0],
    [94, 207, 4, 95, 0],
    [95, 207, 10, 97, 0],
    // entries for 205
    [96, 205, 1, 95, 0],
    [97, 205, 2, 95, 0],
    [98, 205, 3, 95, 0],
    [99, 205, 4, 95, 0],
    [100, 205, 10, 98, 0],
    // entries for 204
    [101, 204, 1, 90, 0],
    [102, 204, 2, 90, 0],
    [103, 204, 3, 90, 0],
    [104, 204, 4, 95, 0],
    [105, 204, 10, 90, 0],
    // entries for 218
    [106, 218, 1, 95, 0],
    [107, 218, 2, 95, 0],
    [108, 218, 3, 95, 0],
    [109, 218, 4, 95, 0],
    [110, 218, 10, 97, 0]
];

// NOTE: This is partial. Real restore would scan the whole file for all IDs in $pencacah_data. 
// But since I cannot read the whole file URL easily here, this is a best effort or I can add a way to parse local file.
// I will attempt to read the local file line by line to get *all* matchingilaian.

$handle = fopen('u927936405_spaneng.sql', "r");
if ($handle) {
    echo "Scanning file for all_penilaian linked to restored pencacah IDs...\n";
    $pencacah_ids = array_column($pencacah_data, 0);
    $count_penilaian = 0;

    $stmt_pen = $conn->prepare("INSERT IGNORE INTO all_penilaian (id, all_kegiatan_pencacah_id, kriteria_id, nilai, t_bobot) VALUES (?, ?, ?, ?, ?)");

    while (($line = fgets($handle)) !== false) {
        if (strpos($line, "INSERT INTO `all_penilaian`") !== false) {
            // Need to parse values. It might be multi-line or single line.
            // Simplified: look for lines starting with "(" inside the values block
            // or just regex the whole block if possible.
            // Since it's huge, I'll regex the line if it contains values.
            // Matches: (id, penc_id, krit_id, val, weight)
            preg_match_all('/\(([0-9]+), ([0-9]+), ([0-9]+), ([0-9.]+), ([0-9.]+)\)/', $line, $matches, PREG_SET_ORDER);
            foreach ($matches as $m) {
                $p_id = $m[2]; // all_kegiatan_pencacah_id
                if (in_array($p_id, $pencacah_ids)) {
                    $stmt_pen->bind_param("iiiid", $m[1], $m[2], $m[3], $m[4], $m[5]);
                    if ($stmt_pen->execute()) {
                        $count_penilaian++;
                    }
                }
            }
        }
        // Also check if lines start with "(" and we are in insert block? 
        // SQL dump usually puts many values on one line or separate.
        // The file viewer showed: (21, ...),\n(22, ...)
        // So I should check lines starting with "("
        if (preg_match('/^\s*\(([0-9]+), ([0-9]+), ([0-9]+), ([0-9.]+), ([0-9.]+)\)[,;]/', $line, $matches)) {
            $p_id = $matches[2];
            if (in_array($p_id, $pencacah_ids)) {
                $stmt_pen->bind_param("iiiid", $matches[1], $matches[2], $matches[3], $matches[4], $matches[5]);
                if ($stmt_pen->execute()) {
                    $count_penilaian++;
                }
            }
        }
    }
    fclose($handle);
    echo "Restored $count_penilaian related entries in all_penilaian.\n";
}

echo "Restore complete!\n";
?>