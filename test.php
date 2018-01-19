<?php

// if (!$_FILES) {
//   echo "Files Gelmedi!";
//   return;
// }
session_start();
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// use PhpOffice\PhpSpreadsheet\Settings;
// Settings::setChartRenderer(\PhpOffice\PhpSpreadsheet\Chart\Renderer\JpGraph::class);
// use PhpOffice\PhpSpreadsheet\Reader\IReader;

// $G42 = (((string)htmlspecialchars($_POST["F27"])) == "0.0" ? 'NP' : htmlspecialchars($_POST["F27"]));
// $H42 = (((string)htmlspecialchars($_POST["F28"])) == "0.0" ? 'NP' : htmlspecialchars($_POST["F28"]));

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
  // $outputFileATT = $outputdir . basename($_FILES['attFile']['name']);
  
  // Burada dosyayi upload dizinine gonderiyor
  move_uploaded_file($_FILES['attFile']['tmp_name'], $uploadfileATT);
}

if (isset($_FILES['elekFile'])) {
  $fileName = $_FILES['elekFile']['name'];
  $elekFileInUploads = $_SESSION['uploaddirELEK'] . basename($_FILES['elekFile']['name']);
  // $outputFile = $_SESSION['outputdir'] . basename($_FILES['elekFile']['name']);

  // Burada dosyayi upload dizinine gonderiyor
  move_uploaded_file($_FILES['elekFile']['tmp_name'], $elekFileInUploads);
  
  // isimleri kontrol ediyorum
  // $elekSTR = substr($_FILES['elekFile']['name'][$i], 4);

  // for ($j = 0; $j < count($_FILES['attFile']['name']); $j++) {

  //   $attSTR = substr($_FILES['attFile']['name'][$j], 3) . 'x';

  //   if ($elekSTR == $attSTR) {
  //     // elek hücreleri degisiyor
  //     $originalATT = $uploaddir . basename($_FILES['attFile']['name'][$j]);
  //     /* $spreadsheetATT = \PhpOffice\PhpSpreadsheet\IOFactory::load($originalATT);
  //     $originalSheet = $spreadsheetATT->getActiveSheet(); */
  //     $readerATT = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($originalATT);
  //     // $readerATT->setIncludeCharts(true);
  //     $spreadsheetATT = $readerATT->load($originalATT);
  //     $sheetOriginalATT = $spreadsheetATT->getActiveSheet();

  //     $F27 = $sheetOriginalATT->getCell('F27')->getCalculatedValue();
  //     $F28 = $sheetOriginalATT->getCell('F28')->getCalculatedValue();


  //     if ($F27 == '') {
  //     // echo 'F27' . $F27;
  //       $F27 = 'NP';
  //     }
  //     if ($F28 == '') {
  //     // echo 'F28' . $F28;
  //       $F28 = 'NP';
  //     }

  //     /* $spreadsheetELEK = \PhpOffice\PhpSpreadsheet\IOFactory::load($uploadfile); */
  //     $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($elekFileInUploads, 'Xlsx');
  //     // $reader->setIncludeCharts(true);
  //     $spreadsheetELEK = $reader->load($elekFileInUploads);
  //     // $spreadsheetATT = \PhpOffice\PhpSpreadsheet\IOFactory::load($originalATT);
  //     // $spreadsheet = $reader->load($uploadfile);
  //     include './chart.php';
  //     $sheetELEK = $spreadsheetELEK->getActiveSheet();
  //     $sheetELEK->setCellValue('G42', $F27)
  //       ->setCellValue('H42', $F28);
      
  //     // // degisen dosya xlsx olarak outputs'a, sonradan indirilmek uzere yaziyor
  //     $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheetELEK, "Xlsx");
  //     // $writer->setIncludeCharts(true);
  //     $writer->save($outputFile);
  //     break;

  //   } else {
  //     // echo 'esit degilmis';
  //     // hücre icerikleri 'NP' olarak degisiyor
  //     $spreadsheetNotELEK = \PhpOffice\PhpSpreadsheet\IOFactory::load($elekFileInUploads);
  //     // $reader->setIncludeCharts(true);
  //     // $spreadsheet = $reader->load($uploadfile);

  //     $sheetELEK = $spreadsheetNotELEK->getActiveSheet();
  //     $sheetELEK->setCellValue('G42', "NP")
  //       ->setCellValue('H42', "NP");
    
  //     // // degisen dosyalar xlsx olarak outputs'a indirilmek uzere yaziyor
  //     $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheetNotELEK, "Xlsx");
  //     // $writer->setIncludeCharts(true);
  //     $writer->save($outputFile);
  //   }
  // }
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

  //
  // $reader->setIncludeCharts(true);
  //
  
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

//   

//   $G27 = ((htmlspecialchars($_POST["G27"])) == '' ? 'NP' : htmlspecialchars($_POST["G27"]));
//   $H27 = ((htmlspecialchars($_POST["H27"])) == '' ? 'NP' : htmlspecialchars($_POST["H27"]));

//   $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("att30.xlsx");
//   $sheet = $spreadsheet->getActiveSheet();
//   $sheet->setCellValue('A1', "G27")
//     ->setCellValue('B1', $G27)
//     ->setCellValue('A2', "H27")
//     ->setCellValue('B2', $H27);

//   $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
//   $writer->save("php://output");



// Check file count

// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
// header('Content-Disposition: attachment;filename="helloworld.xlsx"');
// header('Cache-Control: max-age=0');

