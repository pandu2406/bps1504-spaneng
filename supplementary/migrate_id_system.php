<?php
/**
 * Script PHP untuk Migrasi Sistem ID Survei dan Sensus
 * 
 * CARA MENGGUNAKAN:
 * php migrate_id_system.php
 */

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'spaneng';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "===========================================\n";
echo "   Migrasi Sistem ID Survei dan Sensus\n";
echo "===========================================\n\n";

// Step 1: Cek data saat ini
echo "Step 1: Checking current data...\n";
$survei = $conn->query("SELECT MIN(id) as min_id, MAX(id) as max_id, COUNT(*) as total FROM kegiatan WHERE jenis_kegiatan = 1")->fetch_assoc();
$sensus = $conn->query("SELECT MIN(id) as min_id, MAX(id) as max_id, COUNT(*) as total FROM kegiatan WHERE jenis_kegiatan = 2")->fetch_assoc();

echo "Survei: {$survei['total']} entries (ID {$survei['min_id']} - {$survei['max_id']})\n";
echo "Sensus: {$sensus['total']} entries (ID {$sensus['min_id']} - {$sensus['max_id']})\n\n";

// Step 2: Konfirmasi
if ($sensus['max_id'] >= 1000000) {
    echo "✓ Sensus ID sudah dalam range yang benar (>= 1000000)\n";
    echo "Migration tidak diperlukan.\n";
    exit(0);
}

echo "⚠️  Sensus ID perlu dimigrasikan ke range 1000000+\n\n";
echo "Lanjutkan migration? (y/n): ";
$confirm = trim(fgets(STDIN));

if (strtolower($confirm) !== 'y') {
    die("Migration dibatalkan.\n");
}

// Step 3: Backup
echo "\nStep 2: Creating backup...\n";
$conn->query("DROP TABLE IF EXISTS kegiatan_sensus_backup");
$conn->query("CREATE TABLE kegiatan_sensus_backup AS SELECT * FROM kegiatan WHERE jenis_kegiatan = 2");
echo "✓ Backup created: kegiatan_sensus_backup\n";

// Step 4: Update ID sensus
echo "\nStep 3: Updating Sensus IDs...\n";
$conn->begin_transaction();

try {
    // Update kegiatan
    $conn->query("UPDATE kegiatan SET id = id + 1000000 WHERE jenis_kegiatan = 2 AND id < 1000000");
    echo "✓ Updated kegiatan table\n";

    // Update all_kegiatan_pencacah
    $conn->query("UPDATE all_kegiatan_pencacah akp
                  INNER JOIN kegiatan_sensus_backup ksb ON akp.kegiatan_id = ksb.id
                  SET akp.kegiatan_id = ksb.id + 1000000
                  WHERE ksb.jenis_kegiatan = 2");
    echo "✓ Updated all_kegiatan_pencacah\n";

    // Update all_kegiatan_pengawas
    $conn->query("UPDATE all_kegiatan_pengawas akpw
                  INNER JOIN kegiatan_sensus_backup ksb ON akpw.kegiatan_id = ksb.id
                  SET akpw.kegiatan_id = ksb.id + 1000000
                  WHERE ksb.jenis_kegiatan = 2");
    echo "✓ Updated all_kegiatan_pengawas\n";

    // Update rinciankegiatan
    $conn->query("UPDATE rinciankegiatan rk
                  INNER JOIN kegiatan_sensus_backup ksb ON rk.kegiatan_id = ksb.id
                  SET rk.kegiatan_id = ksb.id + 1000000
                  WHERE ksb.jenis_kegiatan = 2");
    echo "✓ Updated rinciankegiatan\n";

    $conn->commit();
    echo "\n✓ All updates committed successfully!\n";

} catch (Exception $e) {
    $conn->rollback();
    die("\n✗ Error: " . $e->getMessage() . "\n");
}

// Step 5: Create trigger
echo "\nStep 4: Creating trigger for auto ID assignment...\n";
$conn->query("DROP TRIGGER IF EXISTS before_insert_kegiatan");

$trigger_sql = "
CREATE TRIGGER before_insert_kegiatan
BEFORE INSERT ON kegiatan
FOR EACH ROW
BEGIN
    IF NEW.jenis_kegiatan = 2 THEN
        SET @max_sensus_id = (SELECT COALESCE(MAX(id), 999999) FROM kegiatan WHERE jenis_kegiatan = 2);
        IF @max_sensus_id < 1000000 THEN
            SET NEW.id = 1000000;
        ELSE
            SET NEW.id = @max_sensus_id + 1;
        END IF;
    ELSE
        SET @max_survei_id = (SELECT COALESCE(MAX(id), 0) FROM kegiatan WHERE jenis_kegiatan = 1);
        IF @max_survei_id >= 999999 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Survei ID limit reached (max 999999)';
        ELSE
            SET NEW.id = @max_survei_id + 1;
        END IF;
    END IF;
END
";

if ($conn->query($trigger_sql)) {
    echo "✓ Trigger created successfully\n";
} else {
    echo "✗ Trigger creation failed: " . $conn->error . "\n";
}

// Step 6: Verifikasi
echo "\nStep 5: Verification...\n";
$survei_after = $conn->query("SELECT MIN(id) as min_id, MAX(id) as max_id, COUNT(*) as total FROM kegiatan WHERE jenis_kegiatan = 1")->fetch_assoc();
$sensus_after = $conn->query("SELECT MIN(id) as min_id, MAX(id) as max_id, COUNT(*) as total FROM kegiatan WHERE jenis_kegiatan = 2")->fetch_assoc();

echo "\nHasil Setelah Migration:\n";
echo "Survei: {$survei_after['total']} entries (ID {$survei_after['min_id']} - {$survei_after['max_id']})\n";
echo "Sensus: {$sensus_after['total']} entries (ID {$sensus_after['min_id']} - {$sensus_after['max_id']})\n\n";

if ($sensus_after['min_id'] >= 1000000) {
    echo "✓ Migration berhasil!\n";
    echo "✓ Sensus ID sekarang dalam range 1000000+\n";
} else {
    echo "✗ Migration gagal! Sensus ID masih < 1000000\n";
}

echo "\n===========================================\n";
echo "Migration selesai!\n";
echo "===========================================\n";

$conn->close();
