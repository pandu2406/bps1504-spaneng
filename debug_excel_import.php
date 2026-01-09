<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$file = 'assets/excel/excel/data_mitra.xlsx'; // Adjust trace based on user input (user said assets/excel/excel/data_mitra.xlsx)
// Check if file exists there, or in assets/excel/data_mitra.xlsx
if (!file_exists($file)) {
    $file = 'assets/excel/data_mitra.xlsx';
}

if (!file_exists($file)) {
    die("File not found: " . $file);
}

echo "Analyzing File: " . $file . "\n";

try {
    $spreadsheet = IOFactory::load($file);
    $worksheet = $spreadsheet->getActiveSheet();
    $highestRow = $worksheet->getHighestRow();
    $highestColumn = $worksheet->getHighestColumn();

    echo "Highest Row: " . $highestRow . "\n";
    echo "Highest Column: " . $highestColumn . "\n\n";

    echo "--- Row 1 (Header) ---\n";
    print_r($worksheet->rangeToArray('A1:' . $highestColumn . '1', null, true, true, true));

    echo "\n--- Rows 2-5 (First few data rows) ---\n";
    print_r($worksheet->rangeToArray('A2:' . $highestColumn . '5', null, true, true, true));

    echo "\n--- Rows around 170-175 (Near cut-off point?) ---\n";
    // Check if highest row >= 170
    if ($highestRow >= 170) {
        print_r($worksheet->rangeToArray('A170:' . $highestColumn . '175', null, true, true, true));
    } else {
        echo "File has fewer than 170 rows.\n";
    }

    // Specific check for Gender column (Assuming 'H' or 'JK')
    // We'll see from Header output which column is Gender

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>