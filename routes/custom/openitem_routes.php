<?php
// Custom Open Item routes 
Route::get("/openitem", "OpenitemController@openitem")->
	name("openitem.openitem");

Route::get("/openitem/save", "OpenitemController@save")->
	name("openitem.save");


Route::post("/openitem/list", "OpenitemController@listPrdOpenitem")->
	name("openitem.list");


Route::post("/openitem/detail_product", "OpenitemController@detailProduct")->
	name("openitem.detail_product");


Route::post("/openitem/updatecustom", "OpenitemController@updateCustom")->
	name("openitem.updatecustom");


Route::get("/openitem/get_dropDown/{OPTION}/{KEY}", "OpenitemController@get_dropDown")->
	name("openitem.get_dropDown");


Route::post("/openitem/delPicture", "OpenitemController@delPicture")->
	name("openitem.delPicture");



Route::post("/openitem/savePicture", "OpenitemController@savePicture")->
	name("openitem.savePicture");


Route::post("/openitem/update_open", "OpenitemController@updateOpen")->
	name("openitem.update_open");


Route::post("/openitem/delete", "OpenitemController@deleteOpen")->
	name("openitem.delete");

Route::get("/openitem/prdledger/{systemid}", "OpenitemController@prdLedger")->
	name("openitem.prdledger");


Route::get("/openitem/openitem_stockout", "OpenitemController@openitemStockout")->
	name("openitem.openitem_stockout");

Route::get("/openitem/openitem_stockin", "OpenitemController@openitemStockin")->
	name("openitem.openitem_stockin");


Route::post("/openitem/openitem_stockoutlist", "OpenitemController@stockOutList")->
	name("openitem.openitem_stockoutlist");


Route::post("/openitem/openitem_stockinlist", "OpenitemController@stockInList")->
	name("openitem.openitem_stockinlist");



?>
