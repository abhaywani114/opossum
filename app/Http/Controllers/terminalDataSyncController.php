<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Log;
use \DB;

class terminalDataSyncController extends Controller
{
	function syncData() {
		Log::debug('***** syncData() *****');
		try {

			$data = [];	
			$client_ip = request()->ip();
    	    $terminal = DB::table('terminal')->
				where('client_ip', $client_ip)->first();

			$data['master_terminal_id']	= $terminal->id;
			$data['product_id']		= request()->product_id ?? 0;
			$data['pump_no']		= request()->pump_no;
			$data['payment_status'] = request()->payment_status;
			$data['dose']			= request()->dose;
			$data['price']			= request()->price;
			$data['litre']			= request()->litre;
			$data['receipt_id']		= request()->receipt_id;


			Log::debug($data);


			$is_exist = DB::table('mtermsync')->
				where([
					'pump_no'		=> request()->pump_no
				])->first();

			if (!empty($is_exist)) {
				DB::table('mtermsync')->
					where([
						'pump_no'		=> request()->pump_no
					])->update($data);
			} else {
				$data['created_at'] = now();
				$data['updated_at'] = now();
				DB::table('mtermsync')->
					insert($data);
			}

		} catch (\Exception $e) {
			\Log::info([
				"msg"	=> $e->getMessage(),
				"File"	=> $e->getFile(),
				"Line"	=> $e->getLine()	
			]);

			abort(404);
		}
	}


	function getData() {
		//Log::debug('***** getData() *****');
		try {
			$client_ip = request()->ip();
    	    $terminal = DB::table('terminal')->
				where('client_ip', $client_ip)->first();

			$data =  DB::table('mtermsync')->
				leftjoin('product','product.id', 'mtermsync.product_id')->
			/*	where([
					'mtermsync.master_terminal_id'	=> $terminal->id,
					'mtermsync.pump_no'		=> request()->pump_no
				])->*/
				select('mtermsync.*', 'product.systemid as psystemid',
					'product.id as product_id','product.name as pname',
					'product.thumbnail_1')->get();

			//Log::debug('getData: data='.json_encode($data));

			return response()->json($data);

		} catch (Exception $e) {
			Log::info([
				"msg"	=> $e->getMessage(),
				"File"	=> $e->getFile(),
				"Line"	=> $e->getLine()	
			]);

			abort(404);
		}
	}


	function deleteData() {
		Log::debug('***** deleteData() *****');

		try {
			$client_ip = request()->ip();
    	    $terminal = DB::table('terminal')->
				where('client_ip', $client_ip)->first();

			DB::table('mtermsync')->
				where([
					'mtermsync.master_terminal_id'	=> $terminal->id,
					'mtermsync.pump_no'		=> request()->pump_no
				])->delete();

		} catch (\Exception $e) {
			\Log::info([
				"msg"	=> $e->getMessage(),
				"File"	=> $e->getFile(),
				"Line"	=> $e->getLine()	
			]);

			abort(404);
		}
	}
}
