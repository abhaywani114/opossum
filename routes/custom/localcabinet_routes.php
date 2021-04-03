<?php
Route::get('/local_cabinet', 'LocalCabinetController@index')->name('local_cabinet');
Route::post('/local_cabinet/receipt_list',
    'LocalCabinetController@receiptlistTable')->name('local_cabinet.receipt.list');
Route::get('/local_cabinet/eodSummaryPopup/{eod_date?}',
    'LocalCabinetController@eodSummaryPopup')->name('local_cabinet.eodsummary.popup');
Route::get('/local_cabinet/eodReceiptPopup/{id}',
    'LocalCabinetController@eodReceiptPopup')->name('local_cabinet.receipt.popup');

Route::get('/local_cabinet/cstore/eodReceiptPopup/{id}',
    'LocalCabinetController@cstore_receipt')->name('local_cabinet.cstore.receipt.popup');

Route::post('/local_cabinet/eodReceiptVoid',
    'LocalCabinetController@eodReceiptVoid')->name('local_cabinet.receipt.void');
Route::post('/local_cabinet/ReceiptCreate',
    'LocalCabinetController@ReceiptCreate')->name('local_cabinet.receipt.create');
Route::post('/local_cabinet/ReceiptCreateL',
    'LocalCabinetController@ReceiptCreateL')->name('local_cabinet.receipt.create.L');
Route::post('/local_cabinet/ReceiptCreate',
    'LocalCabinetController@ReceiptCreate')->name('local_cabinet.receipt.create');
Route::post('/local_cabinet/receipt/refund',
    'LocalCabinetController@ReceiptRefund')->name('local_cabinet.nozzle.down.refund');

Route::post('/local_cabinet/receipt/refund-cstore',
    'LocalCabinetController@ReceiptRefund_cstore')->name('local_cabinet.cstore.refund');

Route::get('/local_cabinet/ReceiptPrint/{id}',
    'LocalCabinetController@ReceiptPrint')->name('local_cabinet.receipt.print');

Route::post('/local_cabinet/pss-receipt-popup/',
    'LocalCabinetController@pshift_table')->name('pshift.list');
Route::post('/local_cabinet/pss-details/',
    'LocalCabinetController@pshift_detail')->name('pshift.details');

Route::post('/local_cabinet/cstore/create-receipt',
    'LocalCabinetController@ReceiptCreateCStore')->name('local_cabinet.receipt.create.cstore');

Route::post('/local_cabinet/pss-receipt-popup/',
    'LocalCabinetController@pshift_table')->name('pshift.list');

Route::post('/local_cabinet/store_last_filled',
    'LocalCabinetController@store_last_filled')->name('local_cabinet.store_last_filled');

Route::post('/local_cabinet/generate_auth_id',
    'LocalCabinetController@generate_auth_id')->name('local_cabinet.generate_auth_id');

Route::post('/local_cabinet/optList',
    'LocalCabinetController@optList')->name('local_cabinet.optList');


Route::post('/local_cabinet/optListData',
    'LocalCabinetController@optListData')->name('local_cabinet.optListData');

Route::get('/local_cabinet/loyaltyPoints/{id}',
    'LocalCabinetController@loyaltyPoints')->name('local_cabinet.loyaltyPoints');

Route::post('/local_cabinet/checkNric',
    'LocalCabinetController@checkNric')->name('local_cabinet.checkNric');

Route::get('/local_cabinet/cstore-receipt/{date}',
    'LocalCabinetController@cstore_receipt_landing')->name('local_cabinet.cstore_receipt_landing');

?>
