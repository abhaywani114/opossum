<?php
// Custom routes for Oceania Sales Report
Route::get('sales/report','SalesReportController@generate')->
    name('sales.report');
Route::post('sales/print/pdf','SalesReportController@printPDF')->
    name('sales.report.print.pdf');
Route::post('sales/fuel/print/pdf','SalesReportController@fuelPrintPDF')->
    name('sales.fuel.report.print.pdf');

Route::get('sales/terminal/date','SalesReportController@terminalDate')->
    name('sales.terminal.date');
Route::post('sales/report/export/excel',
    'SalesReportController@storeExcel')->name("sales.storeExcel");
?>
