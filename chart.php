<?php
// require 'vendor/autoload.php';
// use PhpOffice\PhpSpreadsheet\Chart\Chart;
// use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
// use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
// use PhpOffice\PhpSpreadsheet\Chart\GridLines;
// use PhpOffice\PhpSpreadsheet\Chart\Legend;
// use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
// use PhpOffice\PhpSpreadsheet\Chart\Title;

// // use PhpOffice\PhpSpreadsheet\IOFactory;
// // use PhpOffice\PhpSpreadsheet\Spreadsheet;

// // $spreadsheet = new Spreadsheet();
// // $worksheet = $spreadsheet->getActiveSheet();
// // $worksheet->fromArray(
// //     [
// //             ['', 2010, 2011, 2012],
// //             ['Q1', 12, 15, 21],
// //             ['Q2', 56, 73, 86],
// //             ['Q3', 52, 61, 69],
// //             ['Q4', 30, 32, 0],
// //         ]
// // );

// //	Set the Labels for each data series we want to plot
// //		Datatype
// //		Cell reference for data
// //		Format Code
// //		Number of datapoints in series
// //		Data values
// //		Data Marker
// // $dataSeriesLabels = [
// //     new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$1', null, 1) //	2010
// // ];

// //	Set the X-Axis Labels
// //		Datatype
// //		Cell reference for data
// //		Format Code
// //		Number of datapoints in series
// //		Data values
// //		Data Marker
// $xAxisTickValues = [
//   new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'buyukelek!$N$15:$N$26', null, 100), //	Q1 to Q4
// ];
// //	Set the Data values for each data series we want to plot
// //		Datatype
// //		Cell reference for data
// //		Format Code
// //		Number of datapoints in series
// //		Data values
// //		Data Marker
// $dataSeriesValues = [
//   new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'buyukelek!$S$15:$S$26', null, 100)
// ];

// //	Build the dataseries
// $series = new DataSeries(
//   DataSeries::TYPE_SCATTERCHART, // plotType
//   null, // plotGrouping (Scatter charts don't have any grouping)
//   range(0, count($dataSeriesValues) - 1), // plotOrder
//   null, // plotLabel
//   $xAxisTickValues, // plotCategory
//   $dataSeriesValues, // plotValues
//   null, // plotDirection
//   null, // smooth line
//   DataSeries::STYLE_LINEMARKER  // plotStyle
// );

// //	Set the series in the plot area
// $plotArea = new PlotArea(null, [$series]);
// //	Set the chart legend
// $legend = new Legend(Legend::POSITION_TOPRIGHT, null, false);

// $title = new Title('');
// $yAxisLabel = new Title('Elekten geçen malzeme (%)');
// $xAxisLabel = new Title('Tane boyu (mm)');

// //	Create the chart
// $chart = new Chart(
//   'chart1', // name
//   $title, // title
//   $legend, // legend
//   $plotArea, // plotArea
//   true, // plotVisibleOnly
//   0, // displayBlanksAs
//   $xAxisLabel, // xAxisLabel
//   $yAxisLabel // yAxisLabel
// );

// //	Set the position where the chart should appear in the worksheet
// $chart->setTopLeftPosition('A13');
// $chart->setBottomRightPosition('K31');

// //	Add the chart to the worksheet
// $sheetELEK->addChart($chart);

// // Save Excel 2007 file
// // $filename = 'test60.xlsx';
// // $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
// // $writer->setIncludeCharts(true);
// // $callStartTime = microtime(true);
// // $writer->save($filename);


foreach ($spreadsheetELEK->getWorksheetIterator() as $worksheet) {
  $sheetName = $worksheet->getTitle();
  // $helper->log('Worksheet: ' . $sheetName);
  $chartNames = $worksheet->getChartNames();
  if (empty($chartNames)) {
      echo '    There are no charts in this worksheet';
  } else {
      natsort($chartNames);
      foreach ($chartNames as $i => $chartName) {
          $chart = $worksheet->getChartByName($chartName);
          if ($chart->getTitle() !== null) {
              $caption = '"' . implode(' ', $chart->getTitle()->getCaption()) . '"';
          } else {
              $caption = 'Untitled';
          }
          // $helper->log('    ' . $chartName . ' - ' . $caption);
          $jpegFile = 'test.jpeg';
          // if (file_exists($jpegFile)) {
          //     unlink($jpegFile);
          // }
          try {
              $chart->render($jpegFile);
              // $helper->log('Rendered image: ' . $jpegFile);
          } catch (Exception $e) {
              // $helper->log('Error rendering chart: ' . $e->getMessage());
          }
      }
  }
}

?>