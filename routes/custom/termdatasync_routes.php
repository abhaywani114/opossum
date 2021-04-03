<?php
	// Custom routes for Terminal DataSync

	Route::post('/sync_data', 'terminalDataSyncController@syncData')->name('sync_data');
	Route::post('/get_sync_data', 'terminalDataSyncController@getData')->name('get_sync_data');
	Route::post('/delete_sync_data', 'terminalDataSyncController@deleteData')->name('delete_sync_data');
?>
