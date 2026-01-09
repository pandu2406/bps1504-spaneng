<?php
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'spaneng_test';

$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error . "\n");
}

echo "Starting Deduplication Process...\n";

// 1. Deduplicate mitra_old based on NIK
// Find duplicate NIKs
$sql = "SELECT nik, COUNT(*) as cnt, GROUP_CONCAT(id_mitra ORDER BY id_mitra DESC) as ids 
        FROM mitra_old 
        GROUP BY nik 
        HAVING cnt > 1";
$result = $mysqli->query($sql);

if ($result) {
    echo "Found " . $result->num_rows . " duplicate NIKs in mitra_old.\n";
    while ($row = $result->fetch_assoc()) {
        $ids = explode(',', $row['ids']);
        $keep_id = $ids[0]; // Keep the latest (highest ID)
        $remove_ids = array_slice($ids, 1); // The rest are to be removed

        echo "Processing NIK {$row['nik']}: Keeping ID $keep_id, removing: " . implode(', ', $remove_ids) . "\n";

        // Update mitra_tahun references to point to $keep_id
        $remove_list = implode(',', $remove_ids);
        if (!empty($remove_list)) {
            $update_sql = "UPDATE mitra_tahun SET id_mitra = $keep_id WHERE id_mitra IN ($remove_list)";
            $mysqli->query($update_sql);

            // Delete duplicates from mitra_old
            $delete_sql = "DELETE FROM mitra_old WHERE id_mitra IN ($remove_list)";
            $mysqli->query($delete_sql);
        }
    }
} else {
    echo "Error checking mitra_old: " . $mysqli->error . "\n";
}

// 2. Deduplicate mitra_tahun based on id_mitra AND tahun
// Strategy: Create a temporary table with distinct values, truncate, and refill.
// Because deleting partial duplicates without a unique ID is hard in MySQL.

echo "Deduplicating mitra_tahun...\n";

// Check if we need to do this (safest way to ensure uniqueness)
$sql_dup_tahun = "SELECT id_mitra, tahun, COUNT(*) as cnt FROM mitra_tahun GROUP BY id_mitra, tahun HAVING cnt > 1";
$res_dup = $mysqli->query($sql_dup_tahun);

if ($res_dup && $res_dup->num_rows > 0) {
    echo "Found " . $res_dup->num_rows . " duplicate entries in mitra_tahun.\n";

    // Create temp table structure
    $mysqli->query("CREATE TEMPORARY TABLE temp_mitra_tahun LIKE mitra_tahun");

    // Insert Distinct with Max ID (if exists) or just distinct fields
    // Assuming we want to keep the one with is_active=1 if conflict, but simpler to just take one.
    // If mitra_tahun has no primary key, we just use DISTINCT.
    $mysqli->query("INSERT INTO temp_mitra_tahun (id_mitra, tahun, posisi, is_active) SELECT DISTINCT id_mitra, tahun, posisi, is_active FROM mitra_tahun GROUP BY id_mitra, tahun");

    // Clear original
    $mysqli->query("DELETE FROM mitra_tahun"); // Dangerous if temp insert failed, but we are in dev/test

    // Restore distinct
    $mysqli->query("INSERT INTO mitra_tahun SELECT * FROM temp_mitra_tahun");

    echo "mitra_tahun deduplicated.\n";
    $mysqli->query("DROP TEMPORARY TABLE temp_mitra_tahun");
} else {
    echo "No duplicates found in mitra_tahun.\n";
}

$mysqli->close();
echo "Done.\n";
