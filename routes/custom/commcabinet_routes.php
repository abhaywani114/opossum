<?php
// Custom Commercial Cabinet routes 
Route::get("/comm_cabinet", "CommcabinetController@commCabinet")->
name("comm_cabinet.commCabinet");
Route::post("/comm_cabinet/list", "CommcabinetController@listCommCabinet")->
name("comm_cabinet.list");

Route::post('/comm_cabinet/commCabinetDetail',
    'CommcabinetController@commCabinetDetail')->name('comm_cabinet.commCabinetDetail');


?>
