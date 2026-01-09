<?php
$mysqli = new mysqli("localhost", "root", "", "spaneng");

if ($mysqli->connect_errno) {
    die("Failed to connect: " . $mysqli->connect_error);
}

echo "<h1>Schema Fix Report</h1>";

// Function to find and drop indexes
function cleanIndex($mysqli, $table, $column)
{
    echo "<h3>Checking indexes on '$column' in table '$table'...</h3>";
    $sql = "SHOW INDEX FROM $table WHERE Column_name = '$column'";
    $result = $mysqli->query($sql);

    $indexes_to_drop = [];
    while ($row = $result->fetch_assoc()) {
        if ($row['Non_unique'] == 0) { // It is a UNIQUE index
            $indexes_to_drop[] = $row['Key_name'];
        }
    }

    // Deduplicate array
    $indexes_to_drop = array_unique($indexes_to_drop);

    if (empty($indexes_to_drop)) {
        echo "No UNIQUE indexes found on column '$column'.<br>";
    } else {
        foreach ($indexes_to_drop as $index) {
            if ($index == 'PRIMARY')
                continue; // Don't drop primary key

            echo "Dropping UNIQUE index: <b>$index</b>... ";
            $dropSql = "ALTER TABLE $table DROP INDEX $index";
            if ($mysqli->query($dropSql)) {
                echo "<span style='color:green'>SUCCESS</span><br>";
            } else {
                echo "<span style='color:red'>FAILED: " . $mysqli->error . "</span><br>";
            }
        }
    }
}

// Execute for critical columns
cleanIndex($mysqli, 'mitra', 'email');
cleanIndex($mysqli, 'mitra', 'nik');

echo "<h3>Final Index State:</h3>";
$res = $mysqli->query("SHOW INDEX FROM mitra");
echo "<table border='1'><tr><th>Key_name</th><th>Column</th><th>Non_unique (1=Yes, 0=No)</th></tr>";
while ($row = $res->fetch_assoc()) {
    echo "<tr><td>{$row['Key_name']}</td><td>{$row['Column_name']}</td><td>{$row['Non_unique']}</td></tr>";
}
echo "</table>";
