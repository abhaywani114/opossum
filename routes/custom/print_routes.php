<?php
	//Custom Routes for Printing
    Route::post('/print_receipt', 'PrintController@print_receipt')->name("receipt.print");
	Route::post('/eod_print', 'PrintController@eod_print')->name('local_cabinet.eod.print');
	Route::post('/pss_print', 'PrintController@PersonalShiftPrint')->name('local_cabinet.pss.print');
	Route::post('/fuel_print', 'PrintController@print_fuel_receipt')->name('receipt.fuel.print');

?>
