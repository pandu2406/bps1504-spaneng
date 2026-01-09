<?php
// Database configuration
$db_config = [
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'spaneng',
    'dbdriver' => 'mysqli',
];

// Connect to database
$conn = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to add column if not exists
function add_column_if_not_exists($conn, $table, $column, $definition)
{
    $check = $conn->query("SHOW COLUMNS FROM $table LIKE '$column'");
    if ($check->num_rows == 0) {
        $sql = "ALTER TABLE $table ADD COLUMN $column $definition";
        if ($conn->query($sql) === TRUE) {
            echo "Column '$column' added to '$table' successfully.\n";
        } else {
            echo "Error adding column '$column': " . $conn->error . "\n";
        }
    } else {
        echo "Column '$column' already exists in '$table'.\n";
    }
}

// Add columns to all_kegiatan_pengawas
add_column_if_not_exists($conn, 'all_kegiatan_pengawas', 'is_notified_assignment', 'INT(1) DEFAULT 0');
add_column_if_not_exists($conn, 'all_kegiatan_pengawas', 'is_notified_completion', 'INT(1) DEFAULT 0');

$conn->close();
?>