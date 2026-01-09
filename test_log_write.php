<?php
$file = 'application/logs/permission_test.txt';
if (file_put_contents($file, "Write test successful at " . date('Y-m-d H:i:s'))) {
    echo "SUCCESS: Wrote to $file";
} else {
    echo "FAILURE: Could not write to $file";
}
?>