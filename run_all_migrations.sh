#!/bin/bash
# Execute all migration scripts on spaneng_test database

echo "==================================================================="
echo "SPANENG Database Restructuring - Testing Mode"
echo "==================================================================="
echo ""

# Database credentials
DB_USER="root"
DB_PASS=""
DB_NAME="spaneng_test"

# Check if mysql is available
if ! command -v mysql &> /dev/null; then
    echo "ERROR: mysql command not found!"
    echo "Using PHP alternative..."
    
    # Use PHP to execute each script
    for script in 001_create_new_tables.sql 002_migrate_master_data.sql 003_migrate_assignments_evaluations.sql 004_cleanup_and_optimize.sql 005_enhanced_features.sql; do
        if [ -f "$script" ]; then
            echo "Executing: $script"
            php -r "\$db = new PDO('mysql:host=127.0.0.1;dbname=$DB_NAME', '$DB_USER', '$DB_PASS'); \$sql = file_get_contents('$script'); \$db->exec(\$sql); echo '  ✓ Done\n';" 2>&1
        else
            echo "  ✗ Script not found: $script"
        fi
    done
else
    # Use mysql CLI
    for script in 001_create_new_tables.sql 002_migrate_master_data.sql 003_migrate_assignments_evaluations.sql 004_cleanup_and_optimize.sql 005_enhanced_features.sql; do
        if [ -f "$script" ]; then
            echo "Executing: $script"
            mysql -u $DB_USER $DB_NAME < $script 2>&1
            if [ $? -eq 0 ]; then
                echo "  ✓ Done"
            else
                echo "  ✗ Error"
            fi
        else
            echo "  ✗ Script not found: $script"
        fi
    done
fi

echo ""
echo "==================================================================="
echo "Verification"
echo "==================================================================="

# Verify tables
php -r "
\$db = new PDO('mysql:host=127.0.0.1;dbname=$DB_NAME', '$DB_USER', '$DB_PASS');
\$tables = \$db->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
echo 'Total tables: ' . count(\$tables) . PHP_EOL;

\$newTables = ['assignments', 'evaluations', 'kegiatans', 'mitras', 'pegawais', 'mitra_years', 'period_types'];
foreach (\$newTables as \$table) {
    if (in_array(\$table, \$tables)) {
        \$count = \$db->query('SELECT COUNT(*) FROM `' . \$table . '`')->fetchColumn();
        echo '  ✓ ' . \$table . ' (' . \$count . ' rows)' . PHP_EOL;
    } else {
        echo '  ✗ ' . \$table . ' (not found)' . PHP_EOL;
    }
}
"

echo ""
echo "Migration completed!"
