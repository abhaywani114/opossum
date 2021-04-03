<?php

Route::get('stocking/show-product-ledger-sale/{product_id}',
	'CentralStockMgmtController@showproductledger')->
	name('stocking.showproductledger');

Route::get('stocking/show-stock-report/{report_id}',
	'CentralStockMgmtController@showStockReport')->
	name('stocking.stock_report');


?>
