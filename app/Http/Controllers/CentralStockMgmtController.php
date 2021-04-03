<?php

namespace App\Http\Controllers;
use App\Classes\SystemID;
use App\Models\Company;
use App\Models\Location;
use App\Models\Product;
use App\Models\PrdOpenitem;
use Illuminate\Http\Request;
use DB;
use Log;

class CentralStockMgmtController extends Controller
{
	public function qtyAvailable($prd_id) {
		try {
			$product_stock = DB::table('stockreportproduct')->
				where('product_id', $prd_id)->
				get()->sum('quantity');
			
				$sales = DB::table('receipt')->
							select('receiptproduct.quantity as quantity')->
							join('receiptproduct','receipt.id','receiptproduct.receipt_id')->
							leftJoin('receiptdetails','receipt.id','receiptdetails.receipt_id')->
							orderBy('receipt.updated_at', "desc")->
							where("receiptproduct.product_id",$prd_id)->
							whereNotIn('receipt.status',['voided'])->
							get()->sum('quantity');

			return $product_stock - $sales;

		} catch (\Exception $e) {
			\Log::info([
				"Error"	=> $e->getMessage(),
				"File"	=> $e->getFile(),
				"Line"	=> $e->getLine()
			]);
			abort(500);
		}
    }
	

	public function bookValAvailable($prd_id) {
		try {
			
			$product_stock = DB::table('fuelmovement')->
				join('prd_ogfuel','prd_ogfuel.id','fuelmovement.ogfuel_id')->
				where('prd_ogfuel.product_id', $prd_id)->
				first();
			
			$receipt = DB::table('stockreportproduct')->
				leftjoin('stockreport', 'stockreport.id',
					'stockreportproduct.stockreport_id')->
				where('stockreportproduct.product_id', $prd_id)->
				get()->sum('quantity');

			return ($product_stock->book ?? 0) + $receipt ?? 0;

		} catch (\Exception $e) {
			\Log::info([
				"Error"	=> $e->getMessage(),
				"File"	=> $e->getFile(),
				"Line"	=> $e->getLine()
			]);
			abort(500);
		}
    }

  
	public function showProductledger(Request $request){
		$product = DB::table('product')->
			where("id",$request->product_id)->first();

		$location = Location::first();
		$data = collect();

		DB::table('receipt')->
			select('receipt.*', 'receiptproduct.quantity as quantity', 'receiptdetails.id as receiptdetails_id')->
			join('receiptproduct','receipt.id','receiptproduct.receipt_id')->
			leftJoin('receiptdetails','receipt.id','receiptdetails.receipt_id')->
			orderBy('receipt.updated_at', "desc")->
			where("receiptproduct.product_id",$request->product_id)->
			get()->
			map(function($product) use ($data) {
				$packet = collect();
				$packet->id			= $product->id;
				$packet->status 	= $product->status;
				$packet->systemid	= $product->systemid;
				$packet->quantity	= $product->quantity * -1;
				$packet->created_at = $product->created_at;
				$packet->voided_at	= $product->voided_at;
				$packet->doc_type	= "Cash Sales";
				$data->push($packet);
			});

		DB::table('stockreportproduct')->
			leftjoin('stockreport','stockreport.id','stockreportproduct.stockreport_id')->
			where('stockreportproduct.product_id', $product->id)->
			orderBy('stockreport.updated_at', "desc")->
			get()->map(function($product) use ($data) {
				$packet = collect();
				$packet->id			= $product->id;
				$packet->status 	= $product->status;
				$packet->systemid	= $product->systemid;
				$packet->quantity	= $product->quantity;
				$packet->created_at = $product->created_at;
				$packet->voided_at	= $product->voided_at ?? "";
				$packet->doc_type	= ucfirst($product->type);
				$data->push($packet);
			});

		$data = $data->sortByDesc('created_at')->values();
		return view("inv_stockmgmt.productledger",
			compact("location","product" ,"data"));
    }


	function stockUpdate(Request $request) {
		try {
			$user_id = \Auth::user()->id;
            $table_data = $request->get('table_data');
			$stock_type = $request->get('stock_type');
			$stock_system = new SystemID("stockreport");

			$company = Company::first();
			$location = Location::first();

			foreach ($table_data as $key => $value) {
				//if qty zero
				if ($value['qty'] <= 0)
					continue;

				//If SI or SO
				if ($stock_type == "IN") {
					$curr_qty 	= $value['qty'];
					$type		=  'stockin';
				} else {
					$curr_qty  = $value['qty'] * -1;
					$type	   = 'stockout';
				}

				//Location Product
				$locationproduct = DB::table('locationproduct')->where([
					'product_id' => $value['product_id']
				])->first();

                if ($locationproduct) { // modify existing location product

					$locationproduct = DB::table('locationproduct')->where([
						'product_id'				=> $value['product_id']
					])->increment('quantity', $curr_qty);

				} else {
					DB::table('locationproduct')->insert([
						"location_id"		=>	$location->id,
						"product_id"		=>	$value['product_id'],
						"quantity"			=>	$curr_qty,
						"damaged_quantity"	=>	0,
						"created_at"		=>	date('Y-m-d H:i:s'),
						'updated_at'		=>  date('Y-m-d H:i:s'),
					]);
				}

				//Stock Report
				$stockreport_id = DB::table('stockreport')->insertGetId([
					'systemid'			=>	$stock_system,
					'creator_user_id'	=>	$user_id,
					'type'				=>	$type,
					'location_id'		=>	$location->id,
					"created_at"		=>	date('Y-m-d H:i:s'),
					'updated_at'		=>	date('Y-m-d H:i:s')
				]);

				DB::table('stockreportproduct')->insert([
					"stockreport_id"	=>	$stockreport_id,
					"product_id"		=>	$value['product_id'],
					"quantity"			=>	$curr_qty,
					"created_at"		=>	date('Y-m-d H:i:s'),
					'updated_at'		=>	date('Y-m-d H:i:s')
				]);
				
				PrdOpenitem::where('product_id', $value['product_id'])->get()->map(function($f) {
					$f->qty = app("App\Http\Controllers\CentralStockMgmtController")->
						qtyAvailable($f->product_id);
					$f->update();
				});

			}
			return response()->json(["status"	=>	true]);
		} catch (\Exception $e) {
			\Log::info([
				"Error"	=> $e->getMessage(),
				"File"	=> $e->getFile(),
				"Line"	=> $e->getLine()
			]);
			abort(500);
		}
	}
	
	public function autoStockIn($product_id, $qty) {
		try {
				$user_id = \Auth::user()->id;
				$stock_system = new SystemID("stockreport");

				$company = Company::first();
				$location = Location::first();

				$type		=  'stockin';
					//Location Product
				$locationproduct = DB::table('locationproduct')->where([
					'product_id' => $product_id
				])->first();

                if ($locationproduct) { // modify existing location product

					$locationproduct = DB::table('locationproduct')->where([
						'product_id'				=> $product_id
					])->increment('quantity', $qty);

				} else {
					DB::table('locationproduct')->insert([
						"location_id"		=>	$location->id,
						"product_id"		=>	$product_id,
						"quantity"			=>	$qty,
						"damaged_quantity"	=>	0,
						"created_at"		=>	date('Y-m-d H:i:s'),
						'updated_at'		=>  date('Y-m-d H:i:s'),
					]);
				}

				//Stock Report
				$stockreport_id = DB::table('stockreport')->insertGetId([
					'systemid'			=>	$stock_system,
					'creator_user_id'	=>	$user_id,
					'type'				=>	$type,
					'location_id'		=>	$location->id,
					"created_at"		=>	date('Y-m-d H:i:s'),
					'updated_at'		=>	date('Y-m-d H:i:s')
				]);

				DB::table('stockreportproduct')->insert([
					"stockreport_id"	=>	$stockreport_id,
					"product_id"		=>	$product_id,
					"quantity"			=>	$qty,
					"created_at"		=>	date('Y-m-d H:i:s'),
					'updated_at'		=>	date('Y-m-d H:i:s')
				]);
				
				PrdOpenitem::where('product_id', $product_id)->get()->map(function($f) {
					$f->qty = app("App\Http\Controllers\CentralStockMgmtController")->
						qtyAvailable($f->product_id);
					$f->update();
				});

		} catch (\Exception $e) {
			\Log::info([
				"Error"	=> $e->getMessage(),
				"File"	=> $e->getFile(),
				"Line"	=> $e->getLine()
			]);
		}
	}

	public function showStockReport() {
		$stockreport = DB::table('stockreport')->
			join('stockreportproduct', 'stockreportproduct.stockreport_id', '=', 'stockreport.id')->
			join('product', 'product.id', '=', 'stockreportproduct.product_id')->
			where('stockreport.systemid', request()->report_id)->get();
	
		$stockreport_data = DB::table('stockreport')->
			select('users.fullname as staff_name', 'users.systemid as staff_id', 
			'stockreport.systemid as document_no', 'stockreport.id as stockreport_id', 
			'stockreport.type as refund_type', 'stockreport.created_at as last_update', 
			'location.name as location', 'location.id as locationid')
			->leftjoin('location', 'location.id', '=', 'stockreport.location_id')
			->join('users', 'users.id', '=', 'stockreport.creator_user_id')
			->where('stockreport.systemid', request()->report_id)->
			orderBy('stockreport.updated_at', "desc")->
			first();

		$isWarehouse = false;
		return view('inv_stockmgmt.inventorystockreport',
			compact('stockreport', 'stockreport_data','isWarehouse'));
	}	
}
