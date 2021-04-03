<?php
Route::get('/screen_e', 'ScreenEController@screen_e')->
	name("screen.e");

Route::post('show-LocationControllerupdate-model','ScreenEController@update')->
	name('location.post.update');

Route::post('location/saveLocationImage','ScreenEController@saveLocationImage')->
	name('location.saveLocationImage');

Route::post('location/deleteLocationImage','ScreenEController@deleteLocationImage')->
	name('location.deleteLocationImage');

Route::get('/cstore_search_product/{a?}', 'ScreenDController@screen_a_products')->
	name('cstore.search');

Route::get('/cstore_scan_product/{a?}', 'ScreenDController@scan_product')->
	name('cstore.scan');
?>
