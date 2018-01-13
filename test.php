<?php

if (!$_FILES) {
  echo "Files Gelmedi!";
  return;
}

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// use PhpOffice\PhpSpreadsheet\Settings;
// Settings::setChartRenderer(\PhpOffice\PhpSpreadsheet\Chart\Renderer\JpGraph::class);
// use PhpOffice\PhpSpreadsheet\Reader\IReader;

// $G42 = (((string)htmlspecialchars($_POST["F27"])) == "0.0" ? 'NP' : htmlspecialchars($_POST["F27"]));
// $H42 = (((string)htmlspecialchars($_POST["F28"])) == "0.0" ? 'NP' : htmlspecialchars($_POST["F28"]));


$keyValueForFolderNames = 'ipek06' . rand();
$uniqueFolderNameForEachUser = md5($keyValueForFolderNames);

mkdir("./uploads/" . $uniqueFolderNameForEachUser, 0777);
mkdir("./outputs/" . $uniqueFolderNameForEachUser, 0777);

$uploaddir = "./uploads/" . $uniqueFolderNameForEachUser . "/";
$outputdir = "./outputs/" . $uniqueFolderNameForEachUser . "/";



/* ATT files moved to  */
for ($j = 0; $j < count($_FILES['attFile']['name']); $j++) {
  $uploadfileATT = $uploaddir . basename($_FILES['attFile']['name'][$j]);
  $outputFileATT = $outputdir . basename($_FILES['attFile']['name'][$j]);

  $fileName = $_FILES['attFile']['name'][$j];
  echo '<pre>';
  // Burada dosyalari upload file'a gonderiyor
  if (move_uploaded_file($_FILES['attFile']['tmp_name'][$j], $uploadfileATT)) {
    echo "File is valid, and was successfully uploaded.\n" . $fileName;
  } else {
    echo "Possible file upload attack!\n" . $fileName;
  }
  print "</pre>";
}







for ($i = 0; $i < count($_FILES['elekFile']['name']); $i++) {
  $uploadfile = $uploaddir . basename($_FILES['elekFile']['name'][$i]);
  $outputFile = $outputdir . basename($_FILES['elekFile']['name'][$i]);

  $fileName = $_FILES['elekFile']['name'][$i];
  echo '<pre>';
  // Burada dosyalari upload file'a gonderiyor
  if (move_uploaded_file($_FILES['elekFile']['tmp_name'][$i], $uploadfile)) {
    echo "File is valid, and was successfully uploaded.\n" . $fileName;
  } else {
    echo "Possible file upload attack!\n" . $fileName;
  }
  print "</pre>";

  $elekSTR = substr($_FILES['elekFile']['name'][$i], 4);

  // isimleri kontrol ediyorum
  for ($j = 0; $j < count($_FILES['attFile']['name']); $j++) {

    $originalATT = $uploaddir . basename($_FILES['attFile']['name'][$j]);
    /* $spreadsheetATT = \PhpOffice\PhpSpreadsheet\IOFactory::load($originalATT);
    $originalSheet = $spreadsheetATT->getActiveSheet(); */
      $readerATT = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($originalATT);
      // $readerATT->setIncludeCharts(true);
      $spreadsheetATT = $readerATT->load($originalATT);
      $sheetOriginalATT = $spreadsheetATT->getActiveSheet();

    $F27 = $sheetOriginalATT->getCell('F27')->getCalculatedValue();
    $F28 = $sheetOriginalATT->getCell('F28')->getCalculatedValue();
    /* $F27 = $originalSheet->getCell('F27')->getCalculatedValue();
    $F28 = $originalSheet->getCell('F28')->getCalculatedValue(); */

    if ($F27 == '') {
      // echo 'F27' . $F27;
      $F27 = 'NP';
    }
    if ($F28 == '') {
      // echo 'F28' . $F28;
      $F28 = 'NP';
    }


    $attSTR = substr($_FILES['attFile']['name'][$j], 3) . 'x';

    // Varsa, yabistir
    if ($elekSTR == $attSTR) {
      // echo 'esitmis';
      // dosyalarin icerikleri degisiyor

      /* $spreadsheetELEK = \PhpOffice\PhpSpreadsheet\IOFactory::load($uploadfile); */
      $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($uploadfile, 'Xlsx');
      // $reader->setIncludeCharts(true);
      $spreadsheetELEK = $reader->load($uploadfile);
      // $spreadsheetATT = \PhpOffice\PhpSpreadsheet\IOFactory::load($originalATT);
      // $spreadsheet = $reader->load($uploadfile);
      include './chart.php';
      $sheetELEK = $spreadsheetELEK->getActiveSheet();
      $sheetELEK->setCellValue('G42', $F27)
        ->setCellValue('H42', $F28);
      
      // // degisen dosyalar xlsx olarak outputs'a indirilmek uzere yaziyor
      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheetELEK, "Xlsx");
      // $writer->setIncludeCharts(true);
      $writer->save($outputFile);

      break;

    } else {
      // echo 'esit degilmis';
      // dosyalarin icerikleri degisiyor
      $spreadsheetNotELEK = \PhpOffice\PhpSpreadsheet\IOFactory::load($uploadfile);
      // $reader->setIncludeCharts(true);
      // $spreadsheet = $reader->load($uploadfile);

      $sheetELEK = $spreadsheetNotELEK->getActiveSheet();
      $sheetELEK->setCellValue('G42', "NP")
        ->setCellValue('H42', "NP");
    
      // // degisen dosyalar xlsx olarak outputs'a indirilmek uzere yaziyor
      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheetNotELEK, "Xlsx");
      // $writer->setIncludeCharts(true);
      $writer->save($outputFile);
    }
  }
}


$zipname = 'ELEK-Files.zip';
$zip = new ZipArchive;
if ($zip->open($zipname, ZipArchive::CREATE) === true) {
  if ($handle = opendir($outputdir)) {
    // Add all files inside the directory
    while (false !== ($entry = readdir($handle))) {
      if ($entry != "." && $entry != ".." && !is_dir($outputdir . '/' . $entry)) {
        $zip->addFile($outputdir . '/' . $entry);
      }
    }
    closedir($handle);
  }
  $zip->close();
}

header('Content-Type: application/zip');
header('Content-disposition: attachment; filename=' . $zipname);
header('Content-Length: ' . filesize($zipname));

ob_clean();
flush();

readfile($zipname);
unlink($zipname);
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

