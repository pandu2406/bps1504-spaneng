<?php
// Script to deep scan u927936405_spaneng.sql for ALL entries related to kegiatan_id 37
$file_path = 'u927936405_spaneng.sql';
$handle = fopen($file_path, "r");

$pencacah_entries = [];
$current_table = '';

if ($handle) {
    while (($line = fgets($handle)) !== false) {
        // Detect table context
        if (strpos($line, 'INSERT INTO `all_kegiatan_pencacah`') !== false) {
            $current_table = 'all_kegiatan_pencacah';
        } elseif (strpos($line, 'INSERT INTO') !== false) {
            $current_table = ''; // Reset if other table
        }

        if ($current_table == 'all_kegiatan_pencacah') {
            // Match values format: (id, kegiatan_id, ...)
            // We want kegiatan_id = 37
            // Regex to find (..., 37, ...)
            // The format in SQL is like: (172, 37, 60, 143)
            // Regex: \(\s*([0-9]+)\s*,\s*37\s*,\s*([0-9]+)\s*,\s*([0-9]+)\s*\)

            if (preg_match_all('/\(\s*([0-9]+)\s*,\s*37\s*,\s*([0-9]+)\s*,\s*([0-9]+)\s*\)/', $line, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $m) {
                    $pencacah_entries[] = [
                        'id' => $m[1],
                        'kegiatan_id' => 37,
                        'id_pengawas' => $m[2],
                        'id_mitra' => $m[3]
                    ];
                }
            }
        }
    }
    fclose($handle);
}

echo "Found " . count($pencacah_entries) . " pencacah entries for kegiatan_id 37.\n";

// Sort by ID to see gaps
usort($pencacah_entries, function ($a, $b) {
    return $a['id'] - $b['id']; });

if (count($pencacah_entries) > 0) {
    echo "First ID: " . $pencacah_entries[0]['id'] . "\n";
    echo "Last ID: " . $pencacah_entries[count($pencacah_entries) - 1]['id'] . "\n";

    // Dump to a JSON file so we can read it easily to insert
    file_put_contents('found_pencacah_37.json', json_encode($pencacah_entries, JSON_PRETTY_PRINT));
}
?>