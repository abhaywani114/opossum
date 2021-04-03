<?php
//Custom Routes for Tank Management & Monitoring
Route::view('/tankmgmt', 'tank/tankmgmt')->name("tank.mgmt");
Route::get("/tankmonitoring", "TankController@tankmonitoring")->name("tank.tankmonitoring");
Route::get("/tankmonitoring/setintervalle", "TankController@tankMonitoringIntervalle")->name("tank.tankMonitoringIntervalle");
Route::post("/tank/save", "TankController@tankSave")->name("tank.save");
Route::post("/tank/list", "TankController@tankList")->name("tank.list");
Route::post("/tank/tankmornitoring/list", "TankController@tankMonitoringList")->name("tank.tankMonitoringList");
Route::post("/tank/update_key", "TankController@updateKey")->name("tank.update_key");
Route::post("/tank/products", "TankController@products")->name("tank.products");
Route::post("/tank/choose/product", "TankController@chooseProduct")->name("tank.chooseproduct");
Route::post("/tank/delete", "TankController@delete")->name("tank.delete");
Route::get("/tank/probeGetTankVolumeForHeight/{probe_no}/{height}/{ipaddr}", "TankController@probeGetTankVolumeForHeight")->name("tank.probeGetTankVolumeForHeight");
Route::get("/tank/probeGetMeasurements/{probe_no}/{ipaddr}", "TankController@probeGetMeasurements")->name("tank.probeGetMeasurements");

?>
