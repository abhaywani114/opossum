<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Yajra\DataTables\DataTables;
use Log;
class VehicleMgmtController extends Controller
{
    //
	public function index() {
    	$index = Null;
            
        return view('vehiclemgmt.vehiclemgmt', compact('index'));
    }


    public function vehiclemgmtDataTable(Request $request){
        try {
            $location   = DB::table('location')->first();
            $location_var = !empty($location->name) ? $location->name : 'Location';
            $data= DB::table('lg_vehiclemgmt')->
				select(DB::raw('lg_vehiclemgmt.* , "'.$location_var.'" as location '))->
				whereNull('deleted_at')->
				orderBy('id', 'DESC')->get();

			return Datatables::of($data)->
				addIndexColumn()->
	
				addColumn('numberPlate', function ($data) {
					$numberPlate = !empty($data->vehicle_license) ?
						$data->vehicle_license : 'Number Plate';
						return <<<EOD
						$numberPlate
EOD;
				})->
				addColumn('location', function ($data) {
						return <<<EOD
						$data->location
EOD;
				})->
				addColumn('act', function ($data) {
					if($data->status=='rfid_active'){ 
						$button= '<button  class="btn-prawn-inactive btn  active_button" id="rfid_status" disabled >Active</button>';
					 }else{ 
						$button= '<button  class="btn-prawn-inactive btn" id="rfid_status" disabled >Active</button>';
				   }
					return <<<EOD
						$button
EOD;
				})->
				escapeColumns([])->
				make(true);

		} catch (Exception $e) {
			Log::info([
				"Error"     => $e->getMessage(),
				"File"      => $e->getFile(),
				"Line No"   => $e->getLine()
			]);
			abort(404);
		}
    }


    public function checkLicKeyResponse(Request $request){
        //$response = "yes";
		$response = array('response' => 'Api response', 'success'=>true);

		$validator = Validator::make($request->all(), [ 
			'license_key' => 'required'
		]);

		$lic_locationkey = null;
		if ($validator->fails()) {
			$response['response'] = $validator->messages();

		} else {
			$lic_locationkey = DB::table('lic_locationkey')->
				where('license_key', $request->
				input('license_key'))->
				where('has_setup',1)->
				get()->first();
		}
		return !empty($lic_locationkey) ? true : false;
    }


    public function postDataToUpdateDownVehicleData(Request $request){
        //$response = "yes";
		$response = array(
			'response' => 'Data inserted',
			'success'=>true
		);

		$validator = Validator::make($request->all(), [ 
			'id' => 'required',
		]);
        $data = array();
		if ($validator->fails()) {
			$response['response'] =
				$validator->messages();

		} else {
			if($request->input('status')){
				$data['status'] =
					$request->input('status');
			}
			if($request->input('vehicle_license')){
				$data['vehicle_license'] = $request->
				input('vehicle_license');
			}
            if($request->input('systemid')){
                $data['systemid'] = $request->
                input('systemid');
            }
            if($request->input('merchant_id')){
                $data['merchant_id'] = $request->
                input('merchant_id');
            }
            if($request->input('deleted_at')){
                $data['deleted_at'] = $request->
                input('deleted_at');
            }

			$lg_vehiclemgmt = DB::table('lg_vehiclemgmt')->
			where('id' , $request->
			input('id'))->get()->first();
            if($lg_vehiclemgmt=== null){

                $data['id'] = $request->input('id');
                $data['created_at'] = date("Y-m-d H:i:s");
                $data['updated_at'] =   date("Y-m-d H:i:s");
                DB::table('lg_vehiclemgmt')->insert($data);
                
            } else {
                $lg_vehiclemgmt = DB::table('lg_vehiclemgmt')->
                where('id' , $request->
                input('id'))->update($data);
            }
		}

		return $response;
        //Log::info($request);;
    }
}
