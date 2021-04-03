<?php
	// Custom routes for EV/Carpark Receipt processing
Route::post('/ev_receipt/evList',
    'EVReceiptController@evList')->name('ev_receipt.evList');
Route::post('/ev_receipt/evListData',
    'EVReceiptController@evListData')->name('ev_receipt.evListData');
Route::post('/ev_receipt/evReceiptDetail',
    'EVReceiptController@evReceiptDetail')->name('ev_receipt.evReceiptDetail');
Route::post('/ev_receipt/evList',
    'EVReceiptController@evList')->name('ev_receipt.evList');

Route::post('/ev_receipt/evListData',
    'EVReceiptController@evListData')->name('ev_receipt.evListData');

?>
