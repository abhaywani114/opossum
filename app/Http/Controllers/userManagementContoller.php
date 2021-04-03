<?php

namespace App\Http\Controllers;

use DB;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use \Log;

class userManagementContoller extends Controller
{

	public function landing(Request $request) {
		try {

			return view('oceania_svr.user.landing');
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

			$data = DB::table('users')->get();

			return Datatables::of($data)->
            	addIndexColumn()->
				addColumn('sysid', function ($staffList) {
					return $staffList->systemid;
				})->
            	addColumn('name', function ($staffList) {
					return (!empty($staffList->fullname) ? $staffList->fullname : 'Name');
				})->
				addColumn('location', function ($staffList) {
					return DB::table('location')->first()->name ?? '';
				})->
            	addColumn('status', function ($staffList) {
					return ucfirst($staffList->status);
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
}
