<?php
// Custom routes for Setup and APIFcController
Route::post('interface/licence-activate',
	'SetupController@licenceInterfaceActivate')->
    name('localaccess.interface.licence');

Route::post('interface/licence-activate-terminal',
	'SetupController@licenceInterfaceActivateTerminal')->
    name('localaccess.interface.licence-terminal');

Route::any('interface/push_data', 'APIFcController@push_fc')->
    name('localaccess.interface.pushdata');

Route::any('interface/update_data', 'SetupController@updateData')->
    name('localaccess.interface.updatedata');

?>
