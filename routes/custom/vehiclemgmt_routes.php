<?php
// Custom Vehicle Management routes 
	Route::get('/vehicle_mgmt','VehicleMgmtController@index')->name('vehicleMgmt');
	Route::post("/vehicle_mgmt/datatable", "VehicleMgmtController@vehiclemgmtDataTable")->
	name("vehicle_mgmt.datatable");	
?>
