<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$I33 = $sheetELEK->getCell('S26')->getCalculatedValue();
$H33 = $sheetELEK->getCell('H33')->getCalculatedValue();
$H38 = $sheetELEK->getCell('H38')->getCalculatedValue();
$H38 = $sheetELEK->getCell('H38')->getCalculatedValue();
$I44 = $sheetELEK->getCell('I44')->getCalculatedValue();
$I45 = $sheetELEK->getCell('I45');
$G33 = $sheetELEK->getCell('G33')->getCalculatedValue();

if ($I33 > 50) {
  $I44 = $I45;
}
if ($I33 >= 12 && $I33 <= 50) {
  if ($G33 > $H33) {
    if ($I44 == 'CL' || $I44 == 'CH') {
      $I45 = 'GC';
    } else {
      $I45 = 'GM';
    }

  } else {
    if ($I44 == 'CL' || $I44 == 'CH') {
      $I45 = 'SC';
    } else {
      $I45 = 'SM';
    }
  }
}
if ($I33 >= 5 && $I33 <= 12) {
  if ($G33 > $H33) {
    if ($I44 == 'CL' || $I44 == 'CH') {
      if ($H38 > 1 && $H38 < 3) {
        if ($H39 > 4) {
          $I45 = 'GW-GC';
        }
      } // 6. Madde 
      else if (($H38 < 1 || $H38 > 3) || $H39 > 4) {
        $I45 = 'GP-GC';
      } // 8. Madde
    } else if (($H38 > 1 && $H38 < 3) && $H39 > 4) {
      $I45 = 'GW-GM';
    } // 7. Madde
    else if (($H38 < 1 || $H38 > 3) || $H39 < 4) {
      $I45 = 'GP-GM';
    } // 9. Madde

  } else if ($H39 > 6 && ($I44 == 'CL' || $I44 == 'CH')) {
    if ($H38 > 1 && $H38 < 3) {
      $I45 = 'SW-SC';
    }
  } // 10. Madde
  else if (($I44 != 'CL' || $I44 != 'CH') && $H39 > 6) {
    if ($H38 > 1 && $H38 < 3) {
      $I45 = 'SW-SM';
    }
  } // 11. Madde
  else if ($H39 < 6 && ($I44 == 'CL' || $I44 == 'CH')) {
    if ($H38 < 1 || $H38 > 3) {
      $I45 = 'SP-SC';
    }
  } // 12. Madde
  else if ($H39 < 6 && ($I44 != 'CL' || $I44 != 'CH')) {
    if ($H38 < 1 || $H38 > 3) {
      $I45 = 'SP-SM';
    }
  } // 13. Madde
}
if ($I33 < 5) {
  if ($G33 > $H33) {
    if ($H38 < 3 && $H38 > 1) {
      if ($H39 > 4) {
        $I45 = 'GW';
      }
    } else if (($H38 < 1 || $H38 > 3) || $H39 < 4) {
      $I45 = 'GP';
    }
  } else if ($H39 > 6 && ($H38 > 1 && $H38 < 3)) {
    $I45 = 'SW';
  } else if ($H39 < 6 && ($H38 < 1 || $H38 > 3)) {
    $I45 = 'SP';
  }
}

$sheetELEK->setCellValue('I45', $I45);
?>