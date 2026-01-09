<?php
/**
 * Reliable Migration Executor
 * Executes migration scripts one by one with proper error handling
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'spaneng_test';

echo "\n";
echo "============================================================\n";
echo "SPANENG Database Migration - Testing Mode\n";
echo "============================================================\n\n";

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✓ Connected to database: $dbName\n\n";

    // List of migration scripts
    $scripts = [
        '001_create_new_tables.sql' => 'Create New Optimized Tables',
        '001b_enhanced_kegiatans_table.sql' => 'Enhanced Kegiatans Table',
        '002_migrate_master_data.sql' => 'Migrate Master Data',
        '003_migrate_assignments_evaluations.sql' => 'Migrate Assignments & Evaluations',
        '004_cleanup_and_optimize.sql' => 'Cleanup & Optimize',
        '005_enhanced_features.sql' => 'Enhanced Features'
    ];

    $executed = 0;
    $skipped = 0;
    $errors = 0;

    foreach ($scripts as $script => $description) {
        echo str_repeat('-', 60) . "\n";
        echo "Script: $script\n";
        echo "Description: $description\n";
        echo str_repeat('-', 60) . "\n";

        if (!file_exists($script)) {
            echo "⚠ SKIPPED: File not found\n\n";
            $skipped++;
            continue;
        }

        try {
            $sql = file_get_contents($script);

            // Remove comments and split by delimiter
            $statements = [];
            $lines = explode("\n", $sql);
            $currentStatement = '';
            $inDelimiter = false;
            $customDelimiter = ';';

            foreach ($lines as $line) {
                $trimmed = trim($line);

                // Skip empty lines and comments
                if (empty($trimmed) || substr($trimmed, 0, 2) === '--') {
                    continue;
                }

                // Handle DELIMITER command
                if (stripos($trimmed, 'DELIMITER') === 0) {
                    $parts = preg_split('/\s+/', $trimmed);
                    if (isset($parts[1])) {
                        $customDelimiter = $parts[1];
                        $inDelimiter = ($customDelimiter !== ';');
                    }
                    continue;
                }

                $currentStatement .= $line . "\n";

                // Check if statement ends
                if (substr(rtrim($line), -strlen($customDelimiter)) === $customDelimiter) {
                    $stmt = trim(substr($currentStatement, 0, -strlen($customDelimiter)));
                    if (!empty($stmt) && substr($stmt, 0, 2) !== '/*') {
                        $statements[] = $stmt;
                    }
                    $currentStatement = '';
                }
            }

            // Add remaining statement if any
            if (!empty(trim($currentStatement))) {
                $statements[] = trim($currentStatement);
            }

            echo "Found " . count($statements) . " SQL statements\n";

            // Execute statements
            $success = 0;
            $failed = 0;

            foreach ($statements as $idx => $statement) {
                try {
                    $pdo->exec($statement);
                    $success++;
                } catch (PDOException $e) {
                    $msg = $e->getMessage();

                    // Ignore certain expected errors
                    if (
                        strpos($msg, 'already exists') !== false ||
                        strpos($msg, 'Duplicate') !== false ||
                        strpos($msg, "doesn't exist") !== false ||
                        strpos($msg, 'Unknown table') !== false
                    ) {
                        // Silent ignore
                    } else {
                        echo "  Error in statement " . ($idx + 1) . ": " . substr($msg, 0, 100) . "...\n";
                        $failed++;
                    }
                }
            }

            echo "✓ Executed: $success statements";
            if ($failed > 0) {
                echo " ($failed errors)";
            }
            echo "\n\n";

            $executed++;

        } catch (Exception $e) {
            echo "✗ ERROR: " . $e->getMessage() . "\n\n";
            $errors++;
        }
    }

    echo "\n";
    echo "============================================================\n";
    echo "MIGRATION SUMMARY\n";
    echo "============================================================\n";
    echo "Executed: $executed\n";
    echo "Skipped:  $skipped\n";
    echo "Errors:   $errors\n\n";

    // Verify database structure
    echo "============================================================\n";
    echo "DATABASE VERIFICATION\n";
    echo "============================================================\n\n";

    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Total tables: " . count($tables) . "\n\n";

    // Check new tables
    $newTables = [
        'assignments' => 'Unified Assignments',
        'evaluations' => 'Unified Evaluations',
        'kegiatans' => 'Enhanced Kegiatans',
        'mitras' => 'Mitra Master',
        'pegawais' => 'Pegawai Master',
        'mitra_years' => 'Mitra per Year',
        'period_types' => 'Period Types',
        'mitra_performance_summary' => 'Performance Summary',
        'report_templates' => 'Report Templates'
    ];

    echo "New Tables Status:\n";
    foreach ($newTables as $table => $desc) {
        if (in_array($table, $tables)) {
            $count = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
            echo "  ✓ $table ($count rows) - $desc\n";
        } else {
            echo "  ✗ $table - NOT FOUND\n";
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
