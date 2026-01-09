<?php
// Simple Migration Executor - Execute one script at a time
if ($argc < 2) {
    die("Usage: php run_migration.php <script_name>\n");
}

$script = $argv[1];

if (!file_exists($script)) {
    die("ERROR: Script $script not found!\n");
}

try {
    $db = new PDO('mysql:host=127.0.0.1;dbname=spaneng_test', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

    echo "Executing: $script\n";
    echo str_repeat('=', 60) . "\n";

    $sql = file_get_contents($script);

    // Execute as multi-query
    $db->exec($sql);

    echo "✓ Script executed successfully!\n\n";

} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
