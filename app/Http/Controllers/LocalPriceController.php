<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Log;
use DB;


use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;

use App\Models\Company;
use App\Models\Location;
use App\Models\Product;
use App\Models\Receipt;

class LocalPriceController extends Controller
{
    function local_price()
    {
        try {

            $data = DB::table('product')->whereNotIn('ptype', ['oilgas'])->get()->count();
            $is_active_all = DB::table('localprice')->
            where([
                "active" => 1,
            ])->get();


            $is_deactive_all = DB::table('localprice')->
            where([
                "active" => 0,
            ])->get();

            $is_all_active = 0;
            if ($is_active_all->count() == $data) {
                $is_all_active = 1;
            }

            if ($is_deactive_all->count() == $data) {
                $is_all_active = 0;
            }

            return view('local_price.landing_screend', compact('is_all_active'));
        } catch (\Exception $e) {
            Log::error([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            abort(404);
        }
    }

	function product_barcode($systemid) {
		try {
			$product = DB::table('product')->
				wheresystemid($systemid)->first();

			return view('local_price.barcode', compact('product'));
		 } catch (\Exception $e) {
            Log::error([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            abort(404);
        }

	}

	function product_barcode_datatable($systemid) {
		try {

			$product = DB::table('product')->
				wheresystemid($systemid)->first();
			

			$barcode = [];
			
			$default = collect();
			$default->systemid = $product->systemid;
			$barcode[] = $default;

			$productbarcode = DB::table('productbarcode')->
				where('product_id', $product->id)->
				get();

			$productbarcode->map(function($f) use ($barcode) {
				$product_barcode = collect();
				
				$product_barcode->systemid = $f->barcode;

				$notes = $barcode->notes;
				$notes .= "Start Date: <b>";
			   	$notes .= date("dMy", strtotime($f->startdate)); 
				$notes .= "</b><br>";
				$notes .= "Expiry Date: <b>";
			   	$notes .= date("dMy", strtotime($f->expirydate)); 
				$notes .= "</b>";
				$product_barcode->notes = $notes;

				$barcode[] = $barcode;
			});


			$bmatrixbarcode = DB::table('productbmatrixbarcode')->
				where('product_id', $product->id)->
				whereNull('deleted_at')->
				orderBy('id', 'desc')->
				get();

			$bmatrixbarcode->map(function($f) use ($barcode) {
				$product_barcode = collect();
			
				$product_barcode->systemid = $f->bmatrixbarcode;

				$barcode[] = $barcode;
			});


			return Datatables::of($barcode)->
				addIndexColumn()->

				addColumn('product_barcode', function ($memberList) {
					$code = new DNS1D();
					$code = $code->getBarcodePNG(trim($memberList->systemid), "C128");
					return <<<EOD
						<img src="data:image/png;base64,$code" style="display:block;"
							 alt="barcode" class="mx-auto" width="200px" height="70px "/> 
						$memberList->systemid
EOD;
				})->

				addColumn('product_qr', function ($memberList) {
					$code = new DNS2D();
					$code = $code->getBarcodePNG($memberList->systemid, "QRCODE");
					return <<<EOD
						<img src="data:image/png;base64,$code" style="display:block;"
							 alt="barcode" class="mx-auto" height='70px' width='70px'/> 
EOD;

				})->

				addColumn('product_color', function ($memberList) {
				})->

				addColumn('product_matrix', function ($memberList) {
				})->

				addColumn('product_notes', function ($memberList) {
					return !empty($memberList->notes) ? $memberList->notes:'';
				})->

				addColumn('product_qty', function ($memberList) {
				})->

				addColumn('product_print', function ($memberList) {
				})->
            	escapeColumns([])->
          	  	make(true);

		} catch (\Exception $e) {
            Log::error([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            abort(404);
        }

	}

    function local_price_datatable(Request $request)
    {
        try {
            $data = DB::table('product')->
				whereIn('product.ptype', ['inventory'])->
				select("product.*", "product.id as z_product_id")->
				get();

			$oridata = $data;

			Log::debug('local_price_datatable: data='.json_encode($data));

			$ndata = $data->filter( function($product) {
				$rec = DB::table('localprice')->
					where('product_id', $product->z_product_id)->
					orderBy('localprice.created_at', 'desc')->
					first();

				$imp = Log::debug('local_price_datatable: z_product_id='.
					$product->z_product_id);

				Log::debug('local_price_datatable: rec='.json_encode($rec));

				if (!empty($rec)) {
					foreach($rec as $key => $value) {
						$product->$key = $value;
					}
					return $product;
				} 
			});

			Log::debug('local_price_datatable: AFTER filter data='.
				json_encode($ndata));

            return Datatables::of($ndata)->
				addIndexColumn()->
				addColumn('product_systemid', function ($memberList) {
					//return $memberList->systemid;
					$url = route("franchise.location_price.barcode", $memberList->systemid);
					return <<<EOD
						<span class="os-linkcolor" style="cursor:pointer" onclick="window.open('$url')">$memberList->systemid</span>
EOD;
            })->

            addColumn('product_name', function ($memberList) {
                $img_src = '/images/product/' .
                    $memberList->systemid . '/thumb/' .
                    $memberList->thumbnail_1;

                $img = "<img src='$img_src' data-field='inven_pro_name' style=' width: 30px;
					height: 30px;display: inline-block;margin-right: 8px;object-fit:contain;'>";

                return $img . $memberList->name;
            })->
            addColumn('product_lower', function ($memberList) {
                return number_format(($memberList->lower_price ?? 0) / 100, 2);
            })->
            addColumn('product_price', function ($data) {
                //	return number_format(($memberList->recommended_price ?? 0) / 100, 2);

                $price = number_format(($data->recommended_price ?? 0) / 100, 2) ?? "0.00";
                $price_inp = $data->recommended_price ?? "";

                $ptype = $data->ptype;

                $validation = $ptype != 'inventory' ? 'bypass' : 'strict';

				Log::debug('local_price_datatable: addColumn product_price:'.
					json_encode($data));

                return <<<EOD
					<span class="os-linkcolor" style="cursor:pointer"
						onclick="updatePrice('$price_inp','$data->z_product_id','$data->lower_price', '$data->upper_price','$validation')">$price
					</span>
EOD;
            })->

            addColumn('product_upper', function ($memberList) {
                return number_format(($memberList->upper_price ?? 0) / 100, 2);
            })->

            addColumn('product_loyalty', function ($memberList) {
            })->

            addColumn('product_stock', function ($memberList) {
                $qty = app("App\Http\Controllers\CentralStockMgmtController")->
                qtyAvailable($memberList->z_product_id);
                $link = route("stocking.showproductledger", $memberList->z_product_id);
                return <<<EOD
					<a href="javascript:window.open('$link')"
						style="text-decoration:none;">$qty
					</a>
EOD;
            })->

            addColumn('product_value', function ($data) {
                $price = number_format(($data->recommended_price ?? 0) / 100, 2) ?? "0.00";
                $qty = app("App\Http\Controllers\CentralStockMgmtController")->
                qtyAvailable($data->z_product_id);
                return $price*$qty;
            })->

            addColumn('active', function ($memberList) {
                $active = $memberList->active == 1 ? "active_button_activated" : '';
                return <<<EOD
				<button
					class="prawn btn active_button $active"
					onclick="activate_func($memberList->z_product_id , this)"
					style="min-width:75px;font-size:14px">Display
				</button>
EOD;

            })->
            escapeColumns([])->
            make(true);

        } catch (Exception $e) {
            Log::error([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            abort(404);
        }
    }


    public function locationPriceUpdate(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                "field" => "required",
                "data" => "required",
                "product_id" => "required"
            ]);

            if ($validation->fails()) {
                new \Exception("Invalid data");
            }


            $is_exist = DB::table('localprice')->
            where([
                "product_id" => $request->product_id
			])->
			orderBy('created_at', 'desc')->
			first();


            $update_array = [];

            Log::debug('locationPriceUpdate: request=' .
                json_encode($request->all()));

            switch ($request->field) {
                case "price":
                    if (!empty($is_exist)) {
                        if ($is_exist->recommended_price == $request->data) {
                            abort(404);
                        }
                    }
                    $update_array['recommended_price'] = (float)$request->data;
                    $msg = ucfirst($request->field) . " updated";
                    break;
                case "active":
                    $update_array['active'] = empty($is_exist->active) ?
                        1 : !$is_exist->active;

                    if ($update_array['active'] == true) {
                        $msg = "Location price has been activated";
                    } else {
                        $msg = "Location price has been deactivated";
                    }
                    break;
            }

            $update_array["updated_at"] = date("Y-m-d H:i:s");

            if (!empty($is_exist)) {
                DB::table('localprice')->
                where('id', $is_exist->id)->
                update($update_array);

            } else {

                $update_array["product_id"] = $request->product_id;
                $update_array["created_at"] = date("Y-m-d H:i:s");

                DB::table('localprice')->
                insert($update_array);
            }

            $response = ["success" => true, "msg" => $msg];


            return response()->json($response);

        } catch (Exception $e) {
            Log::error([
                "error" => $e->getmessage(),
                "file" => $e->getfile(),
                "line" => $e->getline()
            ]);
            abort(404);
        }
    }


    public function priceToggleAll(Request $request)
    {
        try {

            $franchiseid = $request->franchiseid;
            $locationid = $request->locationid;
            $all_btn_state = $request->all_btn_state;
            $date = $request->date;

            $data = DB::table('product')->whereNotIn('ptype', ['oilgas'])->get();

            $data->map(function ($z) use ($all_btn_state) {

                $condition = [
                    "product_id" => $z->id
                ];

                $locationproductprice_data = DB::table('localprice')->
                where($condition)->first();

                if (!empty($locationproductprice_data)) {
                    DB::table('localprice')->
                    where($condition)->update(['active' => !$all_btn_state]);
                } else {
                    $condition['created_at'] = date('Y-m-d H:i:s');
                    $condition['updated_at'] = date('Y-m-d H:i:s');
                    $condition['active'] = !$all_btn_state;
                    DB::table('localprice')->
                    insert($condition);
                }

            });

            if (!$all_btn_state == true) {
                $msg = "All location price has been activated";
            } else {
                $msg = "All location price has been deactivated";
            }

            $response = ["success" => true, "msg" => $msg];

            return response()->json($response);
        } catch (\Exception $e) {
            \Log::info([
                "error" => $e->getmessage(),
                "file" => $e->getfile(),
                "line" => $e->getline()
            ]);
            abort(404);
        }
    }

    //-----------------------------------------------------------------
    public function StockIn()
    {
        $company = Company::first();
        $location = Location::first();
        return view("inv_stockmgmt.stockin", compact('location', 'company'));
    }

    public function StockOut()
    {
        $company = Company::first();
        $location = Location::first();
        return view("inv_stockmgmt.stockout", compact('location', 'company'));
    }

    public function stockInDatatable(Request $request)
    {
        $data = Product::query()->where('ptype', "inventory")->get();

        if ($request->type == 'out') {
            $data = $data->filter(function ($product) {
                return app("App\Http\Controllers\CentralStockMgmtController")->
                    qtyAvailable($product->id) > 0;
            });
        }
        return Datatables::of($data)->
        addIndexColumn()->
        addColumn('product_name', function ($data) {
            $img_src = '/images/product/' .
                $data->systemid . '/thumb/' .
                $data->thumbnail_1;

            $img = "<img src='$img_src' data-field='inven_pro_name' style=' width: 25px;
			height: 25px;display: inline-block;margin-right: 8px;object-fit:contain;'>";

            return $img . $data->name;
        })->addColumn('inven_existing_qty', function ($data) {
            $product_id = $data->id;
            $qty = app("App\Http\Controllers\CentralStockMgmtController")->
            qtyAvailable($product_id);
            //$qty = number_format($qty, 2);
            return <<<EOD
			<span id="qty_$product_id">$qty</span>
EOD;
        })->addColumn('inven_qty', function ($data) {
            $product_id = $data->id;
            return view('fuel_stockmgmt.inven_qty', compact('product_id'));
        })
            ->rawColumns(['inven_existing_qty', 'inven_qty', 'product_name'])
            ->make(true);
    }
}
