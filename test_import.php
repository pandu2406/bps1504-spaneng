<?php
/**
 * Test Import Script
 * Simulates Excel import to debug the issue
 */

// Simulate Excel data
$testData = [
    [
        'nik' => '3201234567890001',
        'nama' => 'Test Mitra 1',
        'posisi' => 'PPL',
        'email' => 'test1@email.com',
        'kecamatan' => '320101',
        'desa' => '001',
        'alamat' => 'Test Address 1',
        'jk' => 'L',
        'no_hp' => '081234567890',
        'sobat_id' => 'SOBAT001'
    ],
    [
        'nik' => '3201234567890002',
        'nama' => 'Test Mitra 2',
        'posisi' => 'PPL',
        'email' => 'test2@email.com',
        'kecamatan' => '320101',
        'desa' => '002',
        'alamat' => 'Test Address 2',
        'jk' => 'P',
        'no_hp' => '081234567891',
        'sobat_id' => 'SOBAT002'
    ],
    [
        'nik' => '3201234567890003',
        'nama' => 'Test Mitra 3',
        'posisi' => 'PML',
        'email' => 'test3@email.com',
        'kecamatan' => '320102',
        'desa' => '001',
        'alamat' => 'Test Address 3',
        'jk' => 'L',
        'no_hp' => '081234567892',
        'sobat_id' => 'SOBAT003'
    ]
];

$tahun = 2026;

try {
    $db = new PDO('mysql:host=127.0.0.1;dbname=spaneng_test', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Testing import logic for year $tahun\n";
    echo str_repeat('=', 60) . "\n\n";

    $count = 0;
    $errors = [];

    foreach ($testData as $m) {
        echo "Processing: {$m['nama']} (NIK: {$m['nik']})\n";

        try {
            // Step 1: Check if mitra exists by NIK
            $stmt = $db->prepare("SELECT * FROM mitra WHERE nik = ?");
            $stmt->execute([$m['nik']]);
            $existing_mitra = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing_mitra) {
                echo "  - Mitra exists (ID: {$existing_mitra['id_mitra']}), updating...\n";
                $id_mitra = $existing_mitra['id_mitra'];

                // Update biodata
                $stmt = $db->prepare("UPDATE mitra SET nama=?, email=?, kecamatan=?, desa=?, alamat=?, jk=?, no_hp=?, sobat_id=? WHERE id_mitra=?");
                $stmt->execute([
                    $m['nama'],
                    $m['email'],
                    $m['kecamatan'],
                    $m['desa'],
                    $m['alamat'],
                    $m['jk'],
                    $m['no_hp'],
                    $m['sobat_id'],
                    $id_mitra
                ]);
                echo "  - Biodata updated\n";

            } else {
                echo "  - New mitra, inserting...\n";

                // Insert new mitra
                $stmt = $db->prepare("INSERT INTO mitra (nik, nama, email, kecamatan, desa, alamat, jk, no_hp, sobat_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $m['nik'],
                    $m['nama'],
                    $m['email'],
                    $m['kecamatan'],
                    $m['desa'],
                    $m['alamat'],
                    $m['jk'],
                    $m['no_hp'],
                    $m['sobat_id']
                ]);
                $id_mitra = $db->lastInsertId();
                echo "  - Mitra inserted (ID: $id_mitra)\n";
            }

            // Step 2: Register for year
            $stmt = $db->prepare("SELECT * FROM mitra_tahun WHERE id_mitra = ? AND tahun = ?");
            $stmt->execute([$id_mitra, $tahun]);
            $existing_year = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing_year) {
                echo "  - Already registered for $tahun, updating...\n";
                $stmt = $db->prepare("UPDATE mitra_tahun SET posisi=?, is_active=1 WHERE id_mitra=? AND tahun=?");
                $stmt->execute([$m['posisi'], $id_mitra, $tahun]);
            } else {
                echo "  - Registering for $tahun...\n";
                $stmt = $db->prepare("INSERT INTO mitra_tahun (id_mitra, tahun, posisi, is_active) VALUES (?, ?, ?, 1)");
                $stmt->execute([$id_mitra, $tahun, $m['posisi']]);
            }

            echo "  ✓ Success!\n\n";
            $count++;

        } catch (PDOException $e) {
            echo "  ✗ Error: " . $e->getMessage() . "\n\n";
            $errors[] = $m['nik'] . ': ' . $e->getMessage();
        }
    }

    echo str_repeat('=', 60) . "\n";
    echo "RESULT: $count/" . count($testData) . " records processed\n";

    if (!empty($errors)) {
        echo "\nErrors:\n";
        foreach ($errors as $error) {
            echo "  - $error\n";
        }
    }

    // Verify
    echo "\nVerification:\n";
    $result = $db->query("SELECT COUNT(*) FROM mitra_tahun WHERE tahun = $tahun")->fetchColumn();
    echo "  Mitra registered for $tahun: $result\n";

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
