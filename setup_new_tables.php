<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'spaneng';

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 1. Create master_periodisitas
$sql_period = "CREATE TABLE IF NOT EXISTS master_periodisitas (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(50) NOT NULL,
    kata_kunci TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($conn->query($sql_period) === TRUE) {
    echo "Table master_periodisitas created successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

// 2. Populate master_periodisitas
$periods = [
    ['nama' => 'Bulanan', 'kata_kunci' => 'Bulanan'],
    ['nama' => 'Triwulanan', 'kata_kunci' => 'Triwulan,Sakernas'],
    ['nama' => 'Semesteran', 'kata_kunci' => 'Semesteran,Sosial Ekonomi Nasional,Susenas'],
    ['nama' => 'Tahunan', 'kata_kunci' => '']
];

foreach ($periods as $p) {
    $nama = $p['nama'];
    $kunci = $p['kata_kunci'];

    // Check if exists to avoid duplicates
    $check = $conn->query("SELECT id FROM master_periodisitas WHERE nama = '$nama'");
    if ($check->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO master_periodisitas (nama, kata_kunci) VALUES (?, ?)");
        $stmt->bind_param("ss", $nama, $kunci);
        $stmt->execute();
        echo "Inserted period: $nama\n";
    }
}

// 3. Create traffic_log
$sql_log = "CREATE TABLE IF NOT EXISTS traffic_log (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NULL,
    ip_address VARCHAR(45),
    url TEXT,
    method VARCHAR(10),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($conn->query($sql_log) === TRUE) {
    echo "Table traffic_log created successfully\n";
} else {
    echo "Error creating traffic_log: " . $conn->error . "\n";
}

$conn->close();
?>