<?php

namespace App\Http\Controllers;

use DB;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class OutdoorPaymentController extends Controller
{
    //
	public function landing(Request $request) {
		try {
			return view('oceania_svr.outdoor_payment.landing');
		} catch (\Exception $e) {
			\Log::info([
				"Error"	=>	$e->getMessage(),
				"File"	=>	$e->getFile(),
				"Line"	=>	$e->getLine()
			]);
			abort(404);
		}	
	}
	//outdoor_payment/landing.blade

	public function mainDatatable() {
		try {

			$data = DB::table('local_pump')->
				get();
			return Datatables::of($data)->
            	addIndexColumn()->
				addColumn('pump_id', function ($item) {
					return $item->systemid;
				})->
				addColumn('pump_no', function ($item) {
					return $item->pump_no;
				})->
				addColumn('term_id', function ($item) {
					return $item->systemid;
				})->
				addColumn('otp', function ($item) {
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
