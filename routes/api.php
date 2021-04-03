<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/PostDateToOceania', 'AccessController@postDateToOceania');
Route::post('/PostDateToOceaniatwoway', 'AccessController@postDateToOceaniatwoway');
Route::post('/downVehicleData', 'VehicleMgmtController@postDataToDownVehicleData');
Route::post('/updateDownVehicleData', 'VehicleMgmtController@postDataToUpdateDownVehicleData');
Route::post('/checkLicKey', 'VehicleMgmtController@checkLicKeyResponse');
Route::post("/creditaccount/deleteMerchantLinkWithRelation", "CreditAccountController@deleteMerchantLinkWithRelation")->name("creditaccount.deleteMerchantLinkWithRelation");
Route::post("/creditaccount/editMerchantLinkRelation", "CreditAccountController@editMerchantLinkRelation")->name("creditaccount.editMerchantLinkRelation");
Route::post("/creditaccount/changeOnewayRelationStatus", "CreditAccountController@changeOnewayRelationStatus")->name("creditaccount.changeOnewayRelationStatus");
Route::post("/creditaccount/OneWay/deleteMerchantLinkWithRelation", "CreditAccountController@oneWayDeleteMerchantLinkWithRelation")->name("creditaccount.oneWayDeleteMerchantLinkWithRelation");
Route::post("/creditaccount/saveMerchandLink", "CreditAccountController@saveMerchandLink")->name("creditaccount.saveMerchandLink");
Route::post("/creditaccount/saveMerchandLinkOneWay", "CreditAccountController@saveMerchandLinkOneWay")->name("creditaccount.saveMerchandLinkOneWay");

