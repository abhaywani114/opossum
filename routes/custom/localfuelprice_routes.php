<?php



Route::post('/interface/update/product/price',
'LocalFuelPriceController@priceUpdate');

Route::post('/interface/update/product',
'LocalFuelPriceController@productUpdate');

Route::post('/interface/test/receipt',
'LocalFuelPriceController@testReceipt');

