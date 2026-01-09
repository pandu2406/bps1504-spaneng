<?php
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'spaneng_test';

$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error . "\n");
}

echo "Formatting existing data...\n";

// Function to normalize phone number
function normalizePhoneNumber($phone)
{
    // 1. Remove non-numeric characters
    $cleaned = preg_replace('/[^0-9]/', '', $phone);

    // 2. Handle 08... -> 628...
    if (substr($cleaned, 0, 2) === '08') {
        $cleaned = '62' . substr($cleaned, 1);
    }
    // 3. Handle 6208... -> 628...
    elseif (substr($cleaned, 0, 4) === '6208') {
        $cleaned = '62' . substr($cleaned, 3); // Remove '620' then add '62' -> just remove '0'? 
        // 6208 -> remove '0' at index 2?
        // simple: remove leading 62, check for 08, add 62 back

        // Easier: if starts with 620, replace with 62
        $cleaned = preg_replace('/^620/', '62', $cleaned);
    }

    // 4. Default if empty or invalid, return original sanitized
    if (strlen($cleaned) < 5)
        return $cleaned; // Too short to be valid phone likely

    return $cleaned;
}

// Function to Proper Case (Title Case)
// Simple ucwords(strtolower()) is usually good enough for names and places
function toProperCase($string)
{
    return ucwords(strtolower($string));
}

$sql = "SELECT id_mitra, nama, kecamatan, no_hp FROM mitra_old";
$result = $mysqli->query($sql);

if ($result) {
    $count = 0;
    while ($row = $result->fetch_assoc()) {
        $id = $row['id_mitra'];
        $nama_new = $mysqli->real_escape_string(toProperCase($row['nama']));
        // Kecamatan might be code or name? In schema, 'kecamatan' is char(3).
        // Wait, schema says `kecamatan` is char(3). That's a code (e.g., '040').
        // If it's a code, we CANNOT title case it.
        // Let's check if it's numeric.

        $kec_val = $row['kecamatan'];
        // If it looks like a name (contains letters), title case it. otherwise leave it.
        if (preg_match('/[a-zA-Z]/', $kec_val)) {
            $kec_new = $mysqli->real_escape_string(toProperCase($kec_val));
        } else {
            $kec_new = $kec_val; // Keep as code
        }

        $hp_new = $mysqli->real_escape_string(normalizePhoneNumber($row['no_hp']));

        // Only update if changed
        if ($nama_new !== $row['nama'] || $kec_new !== $row['kecamatan'] || $hp_new !== $row['no_hp']) {
            $update_sql = "UPDATE mitra_old SET 
                            nama = '$nama_new', 
                            kecamatan = '$kec_new',
                            no_hp = '$hp_new'
                           WHERE id_mitra = $id";
            $mysqli->query($update_sql);
            $count++;
        }
    }
    echo "Updated formatting for $count rows.\n";
} else {
    echo "Error selecting data: " . $mysqli->error . "\n";
}

$mysqli->close();
