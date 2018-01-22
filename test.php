<?php

session_start();
ini_set('max_execution_time', 300);
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$ATTFILES = [];

if (isset($_POST['hash'])) {
  // $keyValueForFolderNames = 'ipek06' . $_POST['hash'];
  $_SESSION['uniqueFolderNameForEachUser'] = $_POST['hash'];

  mkdir("./uploads/att/" . $_SESSION['uniqueFolderNameForEachUser'], 0777);
  mkdir("./uploads/elek/" . $_SESSION['uniqueFolderNameForEachUser'], 0777);
  mkdir("./outputs/" . $_SESSION['uniqueFolderNameForEachUser'], 0777);

  $_SESSION['uploaddirELEK'] = "./uploads/elek/" . $_SESSION['uniqueFolderNameForEachUser'] . "/";
  $_SESSION['uploaddirATT'] = "./uploads/att/" . $_SESSION['uniqueFolderNameForEachUser'] . "/";
  $_SESSION['outputdir'] = "./outputs/" . $_SESSION['uniqueFolderNameForEachUser'] . "/";
}

if (isset($_POST['areFilesDone'])) {
  checkFileName($_SESSION['uploaddirELEK'], $_SESSION['uploaddirATT']);

  $zipname = 'ELEK-Files.zip';
  $zip = new ZipArchive;
  if ($zip->open($zipname, ZipArchive::CREATE) === true) {
    if ($handle = opendir($_SESSION['outputdir'])) {
      // Add all files inside the directory
      while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != ".." && !is_dir($_SESSION['outputdir'] . $entry)) {
          $zip->addFile($_SESSION['outputdir']  . $entry);
        }
      }
      closedir($handle);
    }
    $zip->close();
  }

  header('Accept-Ranges: bytes');
  header('Content-Type: application/zip');
  header('Content-disposition: attachment; filename=' . $zipname);
  header('Content-Length: ' . filesize($zipname));

  ob_clean();
  flush();

  readfile($zipname);
  unlink($zipname);
  session_destroy();
}

/* ATT files moved to ...  */
if (isset($_FILES['attFile'])) {
  $fileName = $_FILES['attFile']['name'];
  $uploadfileATT = $_SESSION['uploaddirATT'] . basename($_FILES['attFile']['name']);
  
  // Burada dosyayi upload dizinine gonderiyor
  move_uploaded_file($_FILES['attFile']['tmp_name'], $uploadfileATT);
}

if (isset($_FILES['elekFile'])) {
  $fileName = $_FILES['elekFile']['name'];
  $elekFileInUploads = $_SESSION['uploaddirELEK'] . basename($_FILES['elekFile']['name']);

  // Burada dosyayi upload dizinine gonderiyor
  move_uploaded_file($_FILES['elekFile']['tmp_name'], $elekFileInUploads);
}

function checkFileName($elekFolder, $attFolder)
{
  if ($attDizin = opendir($attFolder)) {
    if ($elekDizin = opendir($elekFolder)) {

      while (false !== ($elekDosya = readdir($elekDizin))) {
        if ($elekDosya != "." && $elekDosya != "..") {
          $elekSTR = substr($elekDosya, 4);
          $outputdir = $_SESSION['outputdir'] . $elekDosya;

          while (false !== ($attDosya = readdir($attDizin))) {
            if ($attDosya != "." && $attDosya != "..") {
              $attSTR = substr($attDosya, 3) . 'x';

              if ($elekSTR == $attSTR) {
                transferCellData($attFolder . $attDosya, $elekFolder . $elekDosya, $outputdir);
                break;
              }
            }
          }
          if(file_exists($elekFolder . $elekDosya)){
            setCellToNP($elekFolder . $elekDosya, $outputdir);
          }
        }
      }
      closedir($elekDizin);
    }
    closedir($attDizin);
  }
}

function transferCellData($attFile, $elekFile, $output)
{
  $readerATT = \PhpOffice \PhpSpreadsheet \IOFactory::createReaderForFile ($attFile);
  $spreadsheetATT = $readerATT->load($attFile);
  $sheetOriginalATT = $spreadsheetATT->getActiveSheet();
  $F27 = $sheetOriginalATT->getCell('F27')->getCalculatedValue();
  $F28 = $sheetOriginalATT->getCell('F28')->getCalculatedValue();
  if ($F27 == '') {
    $F27 = 'NP';
  }
  if ($F28 == '') {
    $F28 = 'NP';
  }

  $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($elekFile, 'Xlsx');
  
  $spreadsheetELEK = $reader->load($elekFile);
  $sheetELEK = $spreadsheetELEK->getActiveSheet();
  $sheetELEK->setCellValue('G42', $F27)
  ->setCellValue('H42', $F28);

  // 
  include './chart.php';
  $sheetELEK->addChart($chart);
  // 
  
  $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheetELEK, "Xlsx");

  //
  $writer->setIncludeCharts(true);
  // 

  $writer->save($output);
  unlink($elekFile);
}

function setCellToNP($file, $output)
{
  $spreadsheetNotELEK = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
  $sheetELEK = $spreadsheetNotELEK->getActiveSheet();
  $sheetELEK->setCellValue('G42', "NP")
    ->setCellValue('H42', "NP");

  // 
  include './chart.php';
  $sheetELEK->addChart($chart);
  // 

  $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheetNotELEK, "Xlsx");

  //
  $writer->setIncludeCharts(true);
  // 

  $writer->save($output);
}