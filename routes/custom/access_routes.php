<?php
// Custom routes for Access
Route::get('/', 'AccessController@landing')->name('main.view');
Route::get('/temp-opossum', 'AccessController@landingTemp')->name('main.view.t');
Route::get('/onehost-opossum', 'AccessController@one_host_landing')->name('main.view.onehost');
Route::post('/login', 'AccessController@uPLogin')->name('uPLogin');

Route::post('authorizeDriver/', 'AccessController@authorizeUser');

Route::post('/login', 'AccessController@uPLogin')->name('uPLogin');

Route::any('logout/', 'AccessController@logout')->name('logout');
Route::post('/log2laravel', 'AccessController@log2laravel')->name('log2laravel');
//Route::post('/PostDateToOceania', 'AccessController@PostDateToOceania')->name('PostDateToOceania');

?>
