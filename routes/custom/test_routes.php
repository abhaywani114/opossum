<?php
// Custom test routes 
Route::get('/test/get_all_terminal_status',
	'TestController@get_all_terminal_status')->
    name('test.e1-100.get_all_terminal_status');

Route::post('/test/preauth_req',
	'TestController@preauth_req')->
    name('test.e1-100.preauth_req');

?>
