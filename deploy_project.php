<?php
// deploy_project.php

// --- CONFIGURATION ---
$ftp_host = '46.202.138.202'; // IP from screenshot
$ftp_user = 'u927936405';
$ftp_pass = 'Dwis1234@';
$ftp_path = '/public_html/spaneng/'; // Target directory

$local_file = 'spaneng_deploy.zip';
$unzip_script = 'unzip_installer.php';
$url_trigger = 'https://bps-batanghari.com/spaneng/unzip_installer.php';

// --- STEP 1: ZIP THE PROJECT ---
echo "[1/4] Zipping Project... ";
if (file_exists($local_file))
    unlink($local_file);

$zip = new ZipArchive();
if ($zip->open($local_file, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    die("\nCannot create zip file.");
}

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(realpath('.')),
    RecursiveIteratorIterator::LEAVES_ONLY
);

$exclude_dirs = ['.git', '.github', 'node_modules', 'tests', 'user_guide'];
$exclude_exts = ['zip', 'sql', 'rar'];
$exclude_files = ['deploy_project.php', 'composer.lock'];

foreach ($files as $name => $file) {
    // Skip directories (they would be added automatically)
    if (!$file->isDir()) {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen(realpath('.')) + 1);
        $relativePath = str_replace('\\', '/', $relativePath); // Fix Windows paths

        // Filters
        $skip = false;
        foreach ($exclude_dirs as $dir) {
            if (strpos($relativePath, $dir . '/') === 0)
                $skip = true;
        }
        foreach ($exclude_files as $ex) {
            if ($relativePath == $ex)
                $skip = true;
        }
        if ($skip)
            continue;

        $zip->addFile($filePath, $relativePath);
    }
}
$zip->addFile('unzip_installer.php', 'unzip_installer.php');
$zip->close();
echo "Done. Size: " . round(filesize($local_file) / 1024 / 1024, 2) . " MB\n";

// --- STEP 2: CONNECT TO FTP ---
echo "[2/4] Connecting to FTP ($ftp_host)... ";
$conn_id = ftp_connect($ftp_host) or die("\nCannot connect to $ftp_host. Try without 'ftp.' prefix?");
$login_result = ftp_login($conn_id, $ftp_user, $ftp_pass);

if ($login_result) {
    echo "Connected as $ftp_user\n";
    ftp_pasv($conn_id, true); // Passive mode is safer
} else {
    // Try fallback host if first fail?
    die("\nLogin Failed. Check credentials.");
}

// Create directory if not exists
if (@ftp_chdir($conn_id, $ftp_path)) {
    echo "    Target dir exists: $ftp_path\n";
} else {
    echo "    Creating dir: $ftp_path\n";
    ftp_mkdir($conn_id, $ftp_path);
    ftp_chdir($conn_id, $ftp_path);
}

// --- STEP 3: UPLOAD ---
echo "[3/4] Uploading ZIP (This may take a while)... ";
if (ftp_put($conn_id, $local_file, $local_file, FTP_BINARY)) {
    echo "Success.\n";
} else {
    die("\nUpload Failed.");
}

echo "      Uploading Unzipper... ";
ftp_put($conn_id, 'unzip_installer.php', 'unzip_installer.php', FTP_ASCII);
ftp_close($conn_id);
echo "Done.\n";

// --- STEP 4: TRIGGER EXTRACT ---
echo "[4/4] Triggering Extraction via HTTP... ";
$response = file_get_contents($url_trigger);
echo "Server Response: " . $response . "\n";

// Clean up local
@unlink($local_file);

echo "\n--- DEPLOYMENT COMPLETE ---\n";
echo "Note: Don't forget to define('ENVIRONMENT', 'production') in index.php on the server if not already done.\n";
