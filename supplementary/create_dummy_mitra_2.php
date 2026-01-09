<?php
$mysqli = new mysqli("localhost", "root", "", "spaneng");

if ($mysqli->connect_errno) {
    die("Failed to connect: " . $mysqli->connect_error);
}

// 1. Insert into mitra with a unique NIK to avoid conflicts
$nik = "8888888888888888";
$nama = "Dummy For Delete 2";
$email = "dummy.delete2@test.com";

// Check existing
$check = $mysqli->query("SELECT id_mitra FROM mitra WHERE nik = '$nik'");
if ($check->num_rows > 0) {
    $row = $check->fetch_assoc();
    $id_mitra = $row['id_mitra'];
} else {
    $sql = "INSERT INTO mitra (nik, nama, email, kecamatan, desa, alamat, jk, no_hp, sobat_id) 
            VALUES ('$nik', '$nama', '$email', '3301010', '001', 'Test Address', '1', '08123456789', 'DUMMY002')";
    if ($mysqli->query($sql) === TRUE) {
        $id_mitra = $mysqli->insert_id;
    } else {
        die("Error inserting mitra: " . $mysqli->error);
    }
}

// 2. Insert into mitra_tahun for 2026
$tahun = 2026;
$check_tahun = $mysqli->query("SELECT * FROM mitra_tahun WHERE id_mitra = $id_mitra AND tahun = $tahun");
if ($check_tahun->num_rows == 0) {
    $sql_tahun = "INSERT INTO mitra_tahun (id_mitra, tahun, posisi, is_active) VALUES ($id_mitra, $tahun, 'Mitra Pendataan', 1)";
    if ($mysqli->query($sql_tahun) === TRUE) {
        echo "Dummy mitra created successfully for 2026 with ID: " . $id_mitra;
    } else {
        die("Error inserting mitra_tahun: " . $mysqli->error);
    }
} else {
    echo "Dummy mitra already exists for 2026.";
}
