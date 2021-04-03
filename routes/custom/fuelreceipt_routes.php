<?php
	// Custom routes for Fuel Receipt processing

	Route::get('/fuel-receipt-list/{date}', 'FuelReceiptController@fuelReceipList')->name('fuel-receipt-list');
	Route::post('/create-fuel-list','FuelReceiptController@CreateFuelList')->name('create-fuel-list');
	Route::post('/fuel-list-table','FuelReceiptController@dataTable')->name('fuel-list-table');
	Route::post('/fuel-receipt','FuelReceiptController@fuelReceipt')->name('fuel.envReceipt');
	Route::post('/fuel-refund','FuelReceiptController@fuelRefund')->name('fuel.refund');
	Route::post('/fuel-receipt/voided','FuelReceiptController@voidedReceipt')->name('fuel.voidReceipt');
?>
