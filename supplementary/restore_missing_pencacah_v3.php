<?php
// Memory-based Restore Script for Assessment Data (Robust Version)

$json_data = file_get_contents('found_pencacah_37.json');
$pencacah_entries = json_decode($json_data, true);
$pencacah_ids = array_column($pencacah_entries, 'id');

echo "Targeting " . count($pencacah_ids) . " pencacah IDs.\n";

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'spaneng';

$conn = new mysqli($host, $user, $pass, $db);

// Read entire SQL file
$sql_content = file_get_contents('u927936405_spaneng.sql');

// Regex: (digits, digits, digits, float/digits, float/digits)
$pattern = '/\(\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*([^,)]+)\s*,\s*([^)]+)\s*\)/';

echo "Scanning file content...\n";
preg_match_all($pattern, $sql_content, $matches, PREG_SET_ORDER);
echo "Found " . count($matches) . " total tuples.\n";

$stmt_insert = $conn->prepare("INSERT INTO all_penilaian (all_kegiatan_pencacah_id, kriteria_id, nilai, t_bobot) VALUES (?, ?, ?, ?)");
$stmt_check = $conn->prepare("SELECT id FROM all_penilaian WHERE all_kegiatan_pencacah_id = ? AND kriteria_id = ?");

$restored = 0;
$skipped = 0;

foreach ($matches as $m) {
    $p_id = $m[2]; // pencacah_id
    $k_id = $m[3]; // kriteria_id
    $val = trim($m[4], " '\"");
    $wgt = trim($m[5], " '\"");

    if (in_array($p_id, $pencacah_ids)) {
        // Check if exists
        $stmt_check->bind_param("ii", $p_id, $k_id);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows == 0) {
            // Not found, Insert (Auto ID)
            $stmt_insert->bind_param("iidd", $p_id, $k_id, $val, $wgt);
            if ($stmt_insert->execute()) {
                $restored++;
            }
        } else {
            $skipped++;
        }
    }
}

echo "Restored: $restored\n";
echo "Skipped (Already Exists): $skipped\n";
?>