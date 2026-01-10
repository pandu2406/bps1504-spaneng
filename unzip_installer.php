<?php
// unzip_installer.php
// This script runs ON THE SERVER to unzip the archive

$zip_file = 'spaneng_deploy.zip';
$extract_path = './';

if (!file_exists($zip_file)) {
    die("Error: ZIP file not found ($zip_file).");
}

$zip = new ZipArchive;
$res = $zip->open($zip_file);

if ($res === TRUE) {
    $zip->extractTo($extract_path);
    $zip->close();
    echo "Success: Extracted to " . getcwd();
    unlink($zip_file); // Delete zip after success
    unlink(__FILE__);  // Self-destruct
} else {
    echo "Error: Could not extract zip. Code: " . $res;
}
