<?php
//Local Fuel Routes
Route::post('fuel-local-prices/pump-details',
	'LocalFuelController@fuelLocalPriceDatatable')->
	name('fuel_local_price_pump_detail_datatable');

Route::post('fuel-local-prices/pump-details/modal',
	'LocalFuelController@fuelLocalPriceModal')->
	name('fuel_local_price_pump_detail_modal');

Route::post('fuel-local-prices/datatable',
	'LocalFuelController@showFuelLocalPriceDatatable')->
	name('get_industry_oil_gas_fuel_local_price_datatable');

Route::post('fuel-local-prices/push-hardware',
	'LocalFuelController@localFuelPricePushHardware')->
	name('fuel_local_price.push.hardware');

Route::post('fuel-local-prices/update',
	'LocalFuelController@showFuelLocalPriceUpdate')->
	name('get_industry_oil_gas_fuel_local_price_update');

//Fuel Movement
Route::get('fuel/movement/showOgFuelQualifiedProducts',
	'FuelMovementController@showOgFuelQualifiedProducts')->
	name('fuel_movement.showOgFuelQualifiedProducts');

Route::post('fuel/movement/stockupdate',
	'CentralStockMgmtController@stockUpdate')->
	name('fuel_movement.stockUpdate');

Route::post('fuel/movement/main-datatable',
	'FuelMovementController@mainDatatable')->
	name('fuel_movement.mainDatatable');

Route::get('fuel/movement/showproductledgerSale/{fuel_prod_id}/{date}',
	'FuelMovementController@showproductledgerSale')->
	name('fuel_movement.showproductledgerSale');

Route::get('fuel-movement/showproductledger-receipt/{fuel_prod_id}',
	'FuelMovementController@showproductledgerReceipt')->
    name('fuel_movement.showproductledgerReceipt');
Route::post('fuel/movement/tankdipupdate',
    'FuelMovementController@tankdipUpdate')->
    name('fuel_movement.tankdip.update');
Route::post('fuel/movement/cforwardupdate',
    'FuelMovementController@cForwardUpdate')->
    name('fuel_movement.cforward.update');
Route::get('fuel/movement/showOgFuelProducts',
    'FuelMovementController@showOgFuelProducts')->
    name('fuel_movement.showOgFuelProducts');
Route::post('fuel/movement/fuelmovementmaintable',
	'FuelMovementController@fuelmovementmaintable')->
    name('fuel_movement.fuelmovementmaintable');
Route::get('fuel_movement/showproductledgerreceipt/{fuel_prod_id}/{date}',
	'FuelMovementController@showproductledgersReceipt')->
    name('fuel_movement.showproductledgersReceipt');
Route::post('fuel_movement/showOnlyProductLedgersReceipt',
	'FuelMovementController@showOnlyProductLedgersReceipt')->
    name('fuel_movement.showOnlyProductLedgersReceipt');
Route::get('fuel_movement/fuel-mouv-receipts-table/{id}/{date}','FuelMovementController@dataTable')->
name('fuel_movement.fuel-mouv-receipts-table');

?>
