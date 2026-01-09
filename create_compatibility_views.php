<?php
/**
 * Create Views for Backward Compatibility
 * Maps old table names to _old tables so CI3 can still access them
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "\n";
echo "============================================================\n";
echo "Creating Backward Compatibility Views\n";
echo "============================================================\n\n";

try {
    $db = new PDO('mysql:host=127.0.0.1;dbname=spaneng_test', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✓ Connected to spaneng_test\n\n";

    // Get all _old tables
    $oldTables = $db->query("SHOW TABLES LIKE '%_old'")->fetchAll(PDO::FETCH_COLUMN);

    if (empty($oldTables)) {
        echo "No _old tables found. Checking if tables need renaming...\n\n";

        // Check if we need to rename tables to _old first
        $tablesToRename = [
            'all_kegiatan_pencacah',
            'all_kegiatan_pengawas',
            'all_penilaian',
            'all_penilaian_pengawas',
            'kegiatan',
            'mitra',
            'pegawai',
            'kriteria',
            'subkriteria',
            'rinciankegiatan',
            'lpd',
            'posisi',
            'seksi',
            'satuan',
            'sistempembayaran',
            'user',
            'user_role',
            'user_menu',
            'user_sub_menu',
            'user_access_menu'
        ];

        foreach ($tablesToRename as $table) {
            $result = $db->query("SHOW TABLES LIKE '$table'")->fetch();
            if ($result) {
                echo "Renaming: $table -> {$table}_old\n";
                $db->exec("RENAME TABLE `$table` TO `{$table}_old`");
            }
        }

        echo "\n";
        $oldTables = $db->query("SHOW TABLES LIKE '%_old'")->fetchAll(PDO::FETCH_COLUMN);
    }

    echo "Found " . count($oldTables) . " _old tables\n\n";

    $created = 0;
    $skipped = 0;

    foreach ($oldTables as $oldTable) {
        // Remove _old suffix to get original name
        $originalName = str_replace('_old', '', $oldTable);

        echo "Creating view: $originalName -> $oldTable... ";

        try {
            // Drop view if exists
            $db->exec("DROP VIEW IF EXISTS `$originalName`");

            // Create view
            $db->exec("CREATE VIEW `$originalName` AS SELECT * FROM `$oldTable`");

            echo "✓\n";
            $created++;

        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'already exists') !== false) {
                echo "⚠ Already exists\n";
                $skipped++;
            } else {
                echo "✗ Error: " . substr($e->getMessage(), 0, 50) . "...\n";
            }
        }
    }

    echo "\n";
    echo "============================================================\n";
    echo "SUMMARY\n";
    echo "============================================================\n";
    echo "Views created: $created\n";
    echo "Skipped: $skipped\n\n";

    // Verify
    echo "Verification:\n";
    $views = $db->query("SHOW FULL TABLES WHERE Table_type = 'VIEW'")->fetchAll(PDO::FETCH_COLUMN);
    echo "Total views: " . count($views) . "\n\n";

    // Test access
    echo "Testing access to old table names:\n";
    $testTables = ['user', 'mitra', 'kegiatan', 'pegawai'];
    foreach ($testTables as $table) {
        try {
            $count = $db->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
            echo "  ✓ $table ($count rows)\n";
        } catch (PDOException $e) {
            echo "  ✗ $table (not accessible)\n";
        }
    }

    echo "\n✓ Backward compatibility views created successfully!\n\n";

} catch (PDOException $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n\n";
    exit(1);
}
