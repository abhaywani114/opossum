<?php
// Custom routes for Oceania Server User Management
Route::get('user_management', 'userManagementContoller@landing')->
	name('user_management.landing');

Route::get('user_management/mainDatatable',
	'userManagementContoller@mainDatatable')->
	name('user_management.datatable');

Route::get('terminal', 'terminalController@landing')->
	name('terminal.landing');

Route::post('terminal/mainDatatable', 'terminalController@mainDatatable')->
	name('terminal.datatable');

Route::post('terminal/reset_hardware', 'terminalController@resetHardware')->
	name('terminal.reset_hardware');

Route::get('outdoor-payment', 'OutdoorPaymentController@landing')->
	name('outdoor_payment.landing');

Route::get('outdoor-payment/mainDatatable',
	'OutdoorPaymentController@mainDatatable')->
	name('outdoor_payment.datatable');
?>
