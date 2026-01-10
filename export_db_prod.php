<?php
// Simple Database Export Script
$host = 'localhost';
$user = 'root'; // Assuming local dev is root
$pass = '';
$name = 'spaneng_test';

$mysqli = new mysqli($host, $user, $pass, $name);
$mysqli->select_db($name);
$mysqli->query("SET NAMES 'utf8'");

$tables = array();
$views = array();

$result = $mysqli->query('SHOW FULL TABLES');
while ($row = $result->fetch_row()) {
    $table_name = $row[0];
    $table_type = $row[1]; // 'BASE TABLE' or 'VIEW'

    // Skip specific tables
    if (in_array($table_name, ['logs', 'keys', 'sessions']))
        continue;

    if ($table_type == 'VIEW') {
        $views[] = $table_name;
    } else {
        $tables[] = $table_name;
    }
}

$return = "SET FOREIGN_KEY_CHECKS=0;\n\n";

// 1. EXPORT TABLES (Structure + Data)
foreach ($tables as $table) {
    // Structure
    $row2 = $mysqli->query('SHOW CREATE TABLE ' . $table);
    $row2 = $row2->fetch_row();
    $return .= "\n\n-- Structure for table `$table` --\n";
    $return .= $row2[1] . ";\n\n";

    // Data
    $result = $mysqli->query('SELECT * FROM ' . $table);
    $num_fields = $result->field_count;

    if ($result->num_rows > 0) {
        $return .= "-- Dumping data for table `$table` --\n";
        for ($i = 0; $i < $num_fields; $i++) {
            while ($row = $result->fetch_row()) {
                $return .= 'INSERT INTO ' . $table . ' VALUES(';
                for ($j = 0; $j < $num_fields; $j++) {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = str_replace("\n", "\\n", $row[$j]);
                    if (isset($row[$j])) {
                        $return .= '"' . $row[$j] . '"';
                    } else {
                        $return .= '""';
                    }
                    if ($j < ($num_fields - 1)) {
                        $return .= ',';
                    }
                }
                $return .= ");\n";
            }
        }
    }
    $return .= "\n\n";
}

// 2. EXPORT VIEWS (Structure Only)
foreach ($views as $view) {
    $row2 = $mysqli->query('SHOW CREATE TABLE ' . $view);
    $row2 = $row2->fetch_row();
    $create_sql = $row2[1];

    // Remove DEFINER clause (robust regex)
    // Matches: DEFINER = `user`@`host` (with optional quotes/spaces)
    $create_sql = preg_replace('/DEFINER\s*=\s*[^\s]+\s+/', '', $create_sql);
    $create_sql = str_replace('SQL SECURITY DEFINER', 'SQL SECURITY INVOKER', $create_sql); // Optional: Switch to INVOKER for safety

    $return .= "\n\n-- Structure for view `$view` --\n";
    $return .= $create_sql . ";\n\n";
}

$return .= "SET FOREIGN_KEY_CHECKS=1;\n";

$file = 'spaneng_production.sql';
$handle = fopen($file, 'w+');
fwrite($handle, $return);
fclose($handle);

echo "Database exported to $file successfully. (Ordered: Tables first, then Views)";
