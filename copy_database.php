<?php
/**
 * Copy Database: spaneng -> spaneng_test
 * Complete data transfer with progress tracking
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(600); // 10 minutes

echo "\n";
echo "============================================================\n";
echo "Database Copy: spaneng -> spaneng_test\n";
echo "============================================================\n\n";

try {
    $source = new PDO('mysql:host=127.0.0.1;dbname=spaneng', 'root', '');
    $target = new PDO('mysql:host=127.0.0.1;dbname=spaneng_test', 'root', '');

    $source->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $target->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✓ Connected to both databases\n\n";

    // Disable foreign key checks
    $target->exec('SET FOREIGN_KEY_CHECKS=0');
    $target->exec('SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO"');

    // Get all tables
    $tables = $source->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    $total = count($tables);

    echo "Found $total tables to copy\n\n";

    $copied = 0;
    $totalRows = 0;

    foreach ($tables as $table) {
        echo str_pad("[$copied/$total] $table", 50, '.');

        try {
            // Drop existing table
            $target->exec("DROP TABLE IF EXISTS `$table`");

            // Get CREATE TABLE statement
            $createStmt = $source->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
            $target->exec($createStmt['Create Table']);

            // Count rows
            $rowCount = $source->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();

            if ($rowCount > 0) {
                // Copy data in batches
                $batchSize = 100;
                $offset = 0;
                $rowsCopied = 0;

                while ($offset < $rowCount) {
                    $rows = $source->query("SELECT * FROM `$table` LIMIT $batchSize OFFSET $offset")->fetchAll(PDO::FETCH_ASSOC);

                    if (empty($rows))
                        break;

                    foreach ($rows as $row) {
                        $cols = array_keys($row);
                        $vals = array_values($row);

                        $colNames = '`' . implode('`,`', $cols) . '`';
                        $placeholders = implode(',', array_fill(0, count($cols), '?'));

                        $insert = $target->prepare("INSERT INTO `$table` ($colNames) VALUES ($placeholders)");
                        $insert->execute($vals);
                        $rowsCopied++;
                    }

                    $offset += $batchSize;
                }

                echo " $rowsCopied rows ✓\n";
                $totalRows += $rowsCopied;
            } else {
                echo " 0 rows ✓\n";
            }

            $copied++;

        } catch (PDOException $e) {
            echo " ERROR: " . substr($e->getMessage(), 0, 50) . "...\n";
        }
    }

    // Re-enable foreign keys
    $target->exec('SET FOREIGN_KEY_CHECKS=1');

    echo "\n";
    echo "============================================================\n";
    echo "COPY COMPLETED!\n";
    echo "============================================================\n";
    echo "Tables copied: $copied/$total\n";
    echo "Total rows: $totalRows\n\n";

    // Verify
    $targetTables = $target->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    echo "Verification: spaneng_test has " . count($targetTables) . " tables\n\n";

} catch (PDOException $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n\n";
    exit(1);
}
