<?php
// Custom Credit A/C routes 
Route::get("/creditaccount", "CreditAccountController@creditAccount")->name("creditaccount.get");
Route::get("/creditaccount/ledger/{systemid}", "CreditAccountController@creditAccountLedger")->name("creditaccount.ledger");
Route::post("/creditaccount/list", "CreditAccountController@creditAccountList")->name("creditaccount.list");
Route::post("/creditaccount/list_ledger", "CreditAccountController@creditAccountListLedger")->name("creditaccount.listledger");
Route::post("/creditaccount/listMerchantActive", "CreditAccountController@listMerchantActive")->name("creditaccount.listMerchantActive");

?>
