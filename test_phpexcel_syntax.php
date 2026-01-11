<?php
// Define constants expected by CI or PHPExcel if any (FCPATH is used in controller but not in library usually)
// However, PHPExcel usually works standalone.

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Loading PHPExcel...\n";

require 'assets/phpexcel/Classes/PHPExcel.php';
require 'assets/phpexcel/Classes/PHPExcel/Calculation/Functions.php';
require 'assets/phpexcel/Classes/PHPExcel/Calculation/TextData.php';
require 'assets/phpexcel/Classes/PHPExcel/Reader/Excel5.php';
require 'assets/phpexcel/Classes/PHPExcel/Reader/SYLK.php';
require 'assets/phpexcel/Classes/PHPExcel/Writer/Excel5.php';

echo "Files loaded successfully.\n";

$excel = new PHPExcel();
echo "PHPExcel instantiated.\n";

$writer = new PHPExcel_Writer_Excel5($excel);
echo "Writer instantiated.\n";

echo "Done.\n";
