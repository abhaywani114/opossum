<?php

namespace App\Http\Controllers;

use DB;
use Log;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class terminalController extends Controller
{
    //
	
	public function landing(Request $request) {
		try {

			return view('oceania_svr.terminal.landing');
		} catch (\Exception $e) {
			\Log::info([
				"Error"	=>	$e->getMessage(),
				"File"	=>	$e->getFile(),
				"Line"	=>	$e->getLine()
			]);
			abort(404);
		}	
	}

	public function mainDatatable() {
		try {

			$data 		= DB::table('terminal')->
				join('lic_terminalkey','lic_terminalkey.terminal_id','terminal.id')->
				get();

			$location	= DB::table('location')->first();

			return Datatables::of($data)->
            	addIndexColumn()->
				addColumn('loc_id', function ($item) use ($location) {
					return $location->systemid;
				})->
				addColumn('name', function ($item) use ($location) {
					return $location->name;
				})->
				addColumn('term_id', function ($item) {
					return $item->systemid;
				})->
				addColumn('hw', function ($item) {
					return $item->hw_addr;
				})->
				addColumn('location', function ($item) {
				})->
				addColumn('count', function ($item) {
					$rCount = DB::table('receipt')->
						where('terminal_id', $item->terminal_id)->
						get()->count();
					return $rCount;
				})->
				addColumn('threshold', function ($item) {
					return DB::table('terminalcount')->
						where('terminal_id', $item->terminal_id)->first()->allowed_receipt_count ?? 0;
				})->
				escapeColumns([])->make(true);

		} catch (\Exception $e) {
			\Log::info([
				"Error"	=>	$e->getMessage(),
				"File"	=>	$e->getFile(),
				"Line"	=>	$e->getLine()
			]);
			abort(404);
		}	
	}

	function resetHardware(Request $request) {
		try {
			Log::info('###### resetHardware(Request $request) #################');

			$detectHardware = app('\App\Http\Controllers\SetupController')->getMacLinux();

			Log::info("detectHardware => " . $detectHardware);

			Log::info([
				"terminal_systemid" => $request->terminal_systemid,
				"location_systemid" => $request->location_systemid
			]);

			if (empty($detectHardware)) {
				throw new \Exception("No hardware found");
			}

			if (!empty($request->terminal_systemid)) {
				//terminal  hw addr	
			} elseif (!empty ($request->location_systemid)) {
				//location hw addr
				$location = DB::table('location')->
					where('systemid', $request->location_systemid)->
					first();

				if (empty($location))
					throw new \Exception("location invalid");

				
				DB::table('serveraddr')->
					where([
						'location_id' => $request->location_id,
					])->
					update([
						'hw_addr'	=>	$detectHardware
					]);

			} else {
				throw new \Exception("Validation failed");
			}

			return ['hw_addr' => $detectHardware];
		} catch (\Exception $e) {
			$return = [
				"error"	=>	$e->getMessage(),
				"File"	=>	$e->getFile(),
				"Line"	=>	$e->getLine()
			];

		}
	
		\Log::info($return);
		return $return;
	}
}
