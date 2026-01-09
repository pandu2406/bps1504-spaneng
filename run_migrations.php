<?php
/**
 * Execute Migration Scripts - Simple & Reliable
 * Runs each script individually with proper error handling
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(600);

echo "\n";
echo "============================================================\n";
echo "SPANENG Database Restructuring - Migration Execution\n";
echo "============================================================\n\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=spaneng_test', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✓ Connected to spaneng_test\n\n";

    // Migration scripts in order
    $scripts = [
        '001_create_new_tables.sql',
        '001b_enhanced_kegiatans_table.sql',
        '002_migrate_master_data.sql',
        '003_migrate_assignments_evaluations.sql',
        '004_cleanup_and_optimize.sql',
        '005_enhanced_features.sql'
    ];

    foreach ($scripts as $idx => $script) {
        $num = $idx + 1;
        echo "[$num/6] Executing: $script\n";
        echo str_repeat('-', 60) . "\n";

        if (!file_exists($script)) {
            echo "⚠ SKIPPED: File not found\n\n";
            continue;
        }

        try {
            $sql = file_get_contents($script);

            // Execute as single query (let MySQL handle it)
            $pdo->exec($sql);

            echo "✓ SUCCESS\n\n";

        } catch (PDOException $e) {
            $msg = $e->getMessage();

            // Check if it's a benign error
            if (
                strpos($msg, 'already exists') !== false ||
                strpos($msg, 'Duplicate') !== false
            ) {
                echo "⚠ WARNING: " . substr($msg, 0, 80) . "...\n\n";
            } else {
                echo "✗ ERROR: " . substr($msg, 0, 150) . "...\n\n";
            }
        }
    }

    echo "============================================================\n";
    echo "VERIFICATION\n";
    echo "============================================================\n\n";

    // Check new tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Total tables: " . count($tables) . "\n\n";

    $newTables = [
        'assignments',
        'evaluations',
        'kegiatans',
        'mitras',
        'pegawais',
        'mitra_years',
        'period_types',
        'mitra_performance_summary'
    ];

    echo "New Tables:\n";
    foreach ($newTables as $table) {
        if (in_array($table, $tables)) {
            $count = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
            echo "  ✓ $table ($count rows)\n";
        } else {
            echo "  ✗ $table (NOT FOUND)\n";
        }
    }

    echo "\n";
    echo "============================================================\n";
    echo "MIGRATION COMPLETED!\n";
    echo "============================================================\n\n";

} catch (PDOException $e) {
    echo "\n✗ DATABASE ERROR: " . $e->getMessage() . "\n\n";
    exit(1);
}
