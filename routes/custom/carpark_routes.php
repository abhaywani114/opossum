<?php
// Custom car park

Route::get("/carpark-setting", "CarparkController@carPark")->
name("carpark.car_park");

Route::post("/car_park/list", "CarparkController@listCarPark")->
name("car_park.list");

Route::post("/car_park/opera/list", "CarparkController@listCarParkOpera")->
name("car_park.listOpera");

Route::post("/car_park/updateValue", "CarparkController@updateValue")->
name("car_park.updateValue");

Route::post("/car_park/lotDelete", "CarparkController@lotDelete")->
name("car_park.lotDelete");

Route::get("/car_park/save", "CarparkController@save")->
name("car_park.save");

Route::get("/car_park/loadDefaultRate", "CarparkController@loadDefaultRate")->
name("car_park.loadDefaultRate");

//carparklanding
Route::get("/carpark_landing", "CarparkController@carParkLanding")->
name("car_park_landing");

Route::post("/carparkoper/actionStatus", "CarparkController@actionStatus")->
name("carparkoper.actionStatus");

Route::post("/carparkoper/getRounding", "CarparkController@getRounding")->
name("carparkoper.getRounding");


Route::post("/carparkoper/setEnter", "CarparkController@setEnter")->
name("carparkoper.setEnter");



?>