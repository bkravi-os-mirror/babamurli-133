<?php 

require './../libs/vendor-phpexcel/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Om World !');

$writer = new Xlsx($spreadsheet);
$writer->save('./Om world.xlsx');
  
?>
