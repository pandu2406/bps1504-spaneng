<?php
// Execute Migration Scripts on spaneng_test database
try {
    $db = new PDO('mysql:host=127.0.0.1;dbname=spaneng_test', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to spaneng_test database\n\n";

    // List of migration scripts
    $scripts = [
        '001_create_new_tables.sql',
        '002_migrate_master_data.sql',
        '003_migrate_assignments_evaluations.sql',
        '004_cleanup_and_optimize.sql',
        '005_enhanced_features.sql'
    ];

    foreach ($scripts as $script) {
        if (!file_exists($script)) {
            echo "ERROR: Script $script not found!\n";
            continue;
        }

        echo "Executing: $script\n";
        echo str_repeat('=', 60) . "\n";

        $sql = file_get_contents($script);

        // Split by semicolon but handle DELIMITER changes
        $statements = [];
        $current = '';
        $delimiter = ';';

        $lines = explode("\n", $sql);
        foreach ($lines as $line) {
            $line = trim($line);

            // Skip comments
            if (empty($line) || substr($line, 0, 2) == '--' || substr($line, 0, 2) == '/*') {
                continue;
            }

            // Check for DELIMITER change
            if (stripos($line, 'DELIMITER') === 0) {
                $parts = explode(' ', $line);
                if (isset($parts[1])) {
                    $delimiter = trim($parts[1]);
                }
                continue;
            }

            $current .= $line . "\n";

            // Check if statement ends
            if (substr(rtrim($line), -strlen($delimiter)) === $delimiter) {
                $stmt = trim(substr($current, 0, -strlen($delimiter)));
                if (!empty($stmt)) {
                    $statements[] = $stmt;
                }
                $current = '';
            }
        }

        // Execute statements
        $executed = 0;
        $errors = 0;

        foreach ($statements as $statement) {
            try {
                $db->exec($statement);
                $executed++;
            } catch (PDOException $e) {
                // Ignore certain errors
                $msg = $e->getMessage();
                if (
                    strpos($msg, 'already exists') === false &&
                    strpos($msg, 'Duplicate') === false &&
                    strpos($msg, 'doesn\'t exist') === false
                ) {
                    echo "  ERROR: " . $e->getMessage() . "\n";
                    $errors++;
                }
            }
        }

        echo "  Executed: $executed statements";
        if ($errors > 0) {
            echo " ($errors errors)";
        }
        echo "\n\n";
    }

    echo "\n" . str_repeat('=', 60) . "\n";
    echo "MIGRATION COMPLETED!\n";
    echo str_repeat('=', 60) . "\n\n";

    // Verify tables created
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Total tables in spaneng_test: " . count($tables) . "\n";

    // Count new tables
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
    $found = 0;
    foreach ($newTables as $table) {
        if (in_array($table, $tables)) {
            $found++;
            $count = $db->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
            echo "  ✓ $table ($count rows)\n";
        }
    }

    echo "\nNew tables created: $found/" . count($newTables) . "\n";

} catch (PDOException $e) {
    echo "DATABASE ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
