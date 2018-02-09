<?php

function transferDatasToResultFile($elekXLSX_path, $resultXLSX_activeSheet, $elekColumn, $startELEKRow, $endELEKRow, $startResultRow, $endResultRow, $resultColumn)
{
  // L20 Dolu mu ?
  $L20 = $resultXLSX_activeSheet -> getCellValue('L20');
  if($L20 != '') {
    createNewSheet($sklardangelenisim, $resultXLSX_activeSheet);
    $resultColumn = 'F';
  }
  // 

  $xlsxFileReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($elekXLSX_path);
  $loadedFileInRAM = $xlsxFileReader->load($elekXLSX_path);
  $activeELEKSheet = $loadedFileInRAM->getActiveSheet();
  
  for ($elek_row = $startELEKRow; $elek_row <= $endELEKRow; $elek_row++) {
    $result_cell = $resultColumn . $startResultRow;
    $elek_cell_value = $activeELEKSheet->getCellValue($elekColumn . $elek_row);

    $resultXLSX_activeSheet->setCellValue($result_cell, $elek_cell_value);
    $startResultRow++;
  }
  $G42 = $activeELEKSheet->getCell('G42')->getValue();
  $H42 = $activeELEKSheet->getCell('H42')->getValue();
  $I45 = $activeELEKSheet->getCell('I45')->getValue();
  $I42 = $activeELEKSheet->getCell('I42')->getCalculatedValue();

  $resultXLSX_activeSheet -> setCellValue($resultColumn . '34', $G42);
  $resultXLSX_activeSheet -> setCellValue($resultColumn . '35', $H42);
  $resultXLSX_activeSheet -> setCellValue($resultColumn . '36', $I42);
  $resultXLSX_activeSheet -> setCellValue($resultColumn . '37', $I45);
}

function checkFileNames($currentFileName, $nextFileName) {

  return false || true;
}

function createNewSheet($sheetName, $resultDemoFile) {
  $new_worksheet = clone $resultDemoFile->getSheetByName('demo');
  $clonedWorksheet->setTitle($sheetName);
  $resultDemoFile->addSheet($clonedWorksheet);
  // Olusturdugumuz sheeti aktiflestirecegiz
}