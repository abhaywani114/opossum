<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\FuelReceipt;
use App\Models\FuelReceiptdetails;
use App\Models\Location;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\ReceiptRefund;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class FuelMovementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function fm_guide()
    {
        return view("fuel_movement.fmguide");
    }

    public function fuelStockIn()
    {
        $company = Company::first();
        $location = Location::first();
        return view("fuel_stockmgmt.stockin", compact('location', 'company'));
    }

    public function mainDatatable(Request $request)
    {
        try {
            $product_id = $request->ogfuel_id;

            $data = DB::table('fuelmovement')->
            join('prd_ogfuel', 'fuelmovement.ogfuel_id', 'prd_ogfuel.id')->
            where('prd_ogfuel.product_id', $product_id)->
            select('fuelmovement.*', 'prd_ogfuel.product_id')->
            get();

            return Datatables::of($data)->
            addIndexColumn()->
            addColumn('date', function ($fuel_list) {
            })->
            addColumn('cforward', function ($fuel_list) {
            })->
            addColumn('sales', function ($fuel_list) {
            })->
            addColumn('receipt', function ($fuel_list) {
                $qty = number_format(app("App\Http\Controllers\CentralStockMgmtController")->
                qtyAvailable($fuel_list->product_id), 2);

                $url = route('fuel_movement.showproductledgerReceipt', $fuel_list->id);
                return <<<EOD
						<span class="os-linkcolor" onclick="window.open('$url')" style="cursor:pointer">$qty</span>
EOD;
            })->
            addColumn('book', function ($fuel_list) {
            })->
            addColumn('tank_dip', function ($fuel_list) {
            })->
            addColumn('daily_variance', function ($fuel_list) {
            })->
            addColumn('cumulative', function ($fuel_list) {
            })->
            addColumn('percentage', function ($fuel_list) {
            })->
            escapeColumns([])->
            make(true);

        } catch (\Exception $e) {
            \Log::info([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            abort(500);
        }
    }

    public function fuelProduct(Request $request)
    {
        $data = Product::query()->whereptype('oilgas')->get();

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
            	bookValAvailable($product_id);
            $qty = number_format($qty, 2);
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

    public function fuelStockOut()
    {
        $company = Company::first();
        $location = Location::first();
        return view("fuel_stockmgmt.stockout", compact('location', 'company'));
    }

    public function getOgFuelQualifiedProducts($company_id = null)
    {
        $products_chunck = array();
        $filter = array();

        $ids = DB::table('prd_ogfuel')->get()->
        pluck('product_id');

        $products = DB::table('product')->
        select('product.*', 'prd_ogfuel.id as og_f_id')->
        join('prd_ogfuel', 'product.id', 'prd_ogfuel.product_id')->
        whereIn('product.id', $ids)->
        where([
            ['product.name', '<>', null],
            ['product.photo_1', '!=', null]
        ])->
        get();

        return $products;
    }

    public function showOgFuelQualifiedProducts()
    {
        $products = $this->getOgFuelQualifiedProducts();
        $output = "";
        foreach ($products as $product) {
            $output .= '<button class="btn btn-success bg-enter btn-log sellerbuttonwide ps-function-btn pump_credit_card_product" href_fuel_prod_name="'
                . $product->name . '" href_fuel_prod_id="' . $product->id . '" href_fuel_prod_thumbnail="' . $product->thumbnail_1 . '" href_fuel_prod_systemid="'
                . $product->systemid . '" style="width: 129px !important;"> <span>' . $product->name . ' </span></button>';
        }

        $totalRecords = count($products);

        $response = [
            'data' => $products,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'output' => $output
        ];
        return response()->json($response);
    }

    public function showproductledgerSale(Request $request)
    {
        $product = DB::table('product')->where("id", $request->fuel_prod_id)->first();
        $location = Location::first();
        $receipts = DB::table('fuel_receipt')->
			select('fuel_receipt.*', 'fuel_receiptproduct.quantity as quantity', 'fuel_receiptdetails.id as receiptdetails_id',
				'receiptrefund.qty','receiptrefund.refund_amount')->
			join('fuel_receiptproduct', 'fuel_receipt.id', 'fuel_receiptproduct.receipt_id')
            ->whereDate('fuel_receipt.created_at', '=', date('Y-m-d', strtotime($request->date)))
			->leftJoin('fuel_receiptdetails', 'fuel_receipt.id', 'fuel_receiptdetails.receipt_id')
			->leftJoin('receiptrefund','receiptrefund.receipt_id', 'fuel_receipt.id')
			->where("fuel_receiptproduct.product_id", $request->fuel_prod_id)->orderBy('fuel_receipt.id','desc')->get();
        $date = $request->date;
        $id = $request->fuel_prod_id;
        return view("fuel_movement.productledger_sale", compact("receipts", "location", "product","id","date"));
    }

    public function showproductledgerReceipt(Request $request)
    {
        try {
            $product_id = $request->product_id;

            $stockData = DB::table('stockreport')->
            leftjoin('location', 'location.id', 'stockreport.location_id')->
            join('stockreportproduct', 'stockreportproduct.stockreport_id', 'stockreport.id')->
            select("stockreport.*", 'stockreportproduct.quantity',
                "location.name as location_name")->
            orderBy('stockreport.updated_at', 'desc')->
            where('stockreportproduct.product_id', $product_id)->
            get();

            return view("fuel_movement.productledger_receipt", compact('stockData'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function cForwardUpdate(Request $request)
    {
        DB::table('fuelmovement')->where('id', $request->id)
            ->update([
                "cforward" => $request->c_Forward,
                "book" => $request->book,
                "daily_variance" => $request->daily_variance,
                "cumulative" => $request->cumulative,
                "percentage" => $request->percentage,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        return true;
    }

    public function tankdipUpdate(Request $request)
    {
		$company = DB::table('company')->first();
		$location = DB::table('location')->first();

		$is_exist = 	DB::table('fuelmovement')->
			where('ogfuel_id', $request->ogfuel_id)->
			whereDate('date', date('Y-m-d', strtotime($request->date)))->
			first();

		if (empty($is_exist)) {
			$this->add_fuel_movement($location->id, $request->ogfuel_id, 
					$company->id,  date('Y-m-d', strtotime($request->date)), 0 );
		}
		
		$yesterday_rec = DB::table('fuelmovement')->
			where('ogfuel_id', $request->ogfuel_id)->
			whereDate('date', date('Y-m-d', strtotime('-1 day ' . $request->date)))->
			first();
		
		$prev = DB::table('fuelmovement')->
			where('ogfuel_id', $request->ogfuel_id)->
			whereDate('date', date('Y-m-d', strtotime($request->date)))->
			first();

		$next_rec = DB::table('fuelmovement')->
			where('ogfuel_id', $request->ogfuel_id)->
			whereDate('date', date('Y-m-d', strtotime('+1 day ' . $prev->date)))->
			first();

		$cumulative =  ($yesterday_rec->cumulative ?? 0) + $request->tank_dip - $prev->book;

		if ($prev->book != 0)
			$percentage = abs(($cumulative / $prev->book) * 100) * 100;

		DB::table('fuelmovement')->
			where('ogfuel_id', $request->ogfuel_id)->
			whereDate('date', date('Y-m-d', strtotime($request->date)))->
			update([
                "tank_dip" => $request->tank_dip,
                "daily_variance" => $request->tank_dip - $prev->book,
                "cumulative" => $cumulative,
                "percentage" => $percentage ?? 0, 
                'updated_at' => date('Y-m-d H:i:s'),
            ]);


		if (date('d', strtotime('+1 day '.$prev->date))  != 1) {
			if (!empty($next_rec)) {

				if (!empty($next_rec))
					$book = $request->tank_dip; //- $next_rec->sales; + $next_rec->receipt;

				DB::table('fuelmovement')->where('id', $next_rec->id)->
					update([
						'book'	=> $book ?? 00,
						'cforward' => $request->tank_dip
					]);

			}  else {
				$this->add_fuel_movement($location->id, $request->ogfuel_id, 
					$company->id,  date('Y-m-d', strtotime('+1 day '.$prev->date)), $request->tank_dip);
			}	
		}

        return true;
    }


	public function add_fuel_movement($location_id, $ogfuel_id, $franchisee_company_id, $created_at, $cforward ) {
		DB::table('fuelmovement')->insert([
			"location_id" => $location_id,
			"ogfuel_id" => $ogfuel_id,
			"franchisee_company_id" => $franchisee_company_id,
			"date" => $created_at,
			"cforward" => $cforward,
			"sales" => "0.00",
			"receipt" => "0.00",
			"book" => $cforward,
			"tank_dip" => "0.00",
			"daily_variance" => "0.00",
			"cumulative" => "0.00",
			"percentage" => "0.00",
			"created_at" => date('Y-m-d H:i:s'),
			"updated_at" => date('Y-m-d H:i:s'),
		]);
	}

    public function showOgFuelProducts()
    {
        $ids = DB::table('prd_ogfuel')->get()->
        pluck('product_id');

        $products = DB::table('product')->
        select('product.*', 'prd_ogfuel.id as og_f_id')->
        join('prd_ogfuel', 'product.id', 'prd_ogfuel.product_id')->
        whereIn('product.id', $ids)->
        where('product.ptype', 'oilgas')->
        where([
            ['product.name', '<>', null],
            ['product.photo_1', '!=', null]
        ])->
        get();
        $output = "";

        foreach ($products as $product) {
            $output .= "<div class='col-md-12 ml-0 pl-0'><div class='row align-items-center d-flex'>
			<div class='col-md-2'>
			<img class='thumbnail productselect sellerbutton' href_fuel_thumbnail='' href_fuel_prod_name='" . $product->name . "'
			href_fuel_prod_id='" . $product->id . "' href_fuel_prod_thumbnail='" . $product->thumbnail_1 . "' href_fuel_prod_systemid='" . $product->systemid . "'
			style='padding-top:0;object-fit:contain;float:right;width:30px !important;height:30px !important;margin-left:0;margin-top:2px;margin-right:0;margin-bottom:2px' src='/images/product/" . $product->systemid . "/thumb/" . $product->thumbnail_1 . "'>
			</div>
			<div class='col-md-10 pl-0 productselect' href_fuel_thumbnail=''
			href_fuel_prod_name='" . $product->name . "' href_fuel_prod_id='" . $product->id . "' href_fuel_prod_thumbnail='" . $product->thumbnail_1 . "' href_fuel_prod_systemid='" . $product->systemid . "'
			style='cursor:pointer;line-height:1.2;margin-left:0;font-size:20px;padding-top:0;text-align: left;'>" . $product->name . "</div></div></div>";
        }

        $totalRecords = count($products);
        $response = [
            'data' => $products,
            'output' => $output
        ];
        return response()->json($response);
    }

    public function updateSalescolumn($fuel_prod_id)
    {
        // $product = DB::table('product')->where("id",$fuel_prod_id)->first();
        $qtysum = DB::table('receipt')->
			join('receiptproduct', 'receipt.id', 'receiptproduct.receipt_id')->
			where('status', '!=', 'voided')->where('receipt.created_at', '>=', date('Y-m-d') . ' 00:00:00')
				->where("receiptproduct.product_id", $fuel_prod_id)->sum('receiptproduct.quantity');

		$refund = DB::table('receiptrefund')->
			join('receiptproduct', 'receiptrefund.receipt_id', 'receiptproduct.receipt_id')->
			where("receiptproduct.product_id", $fuel_prod_id)->
			where('receiptrefund.created_at', '>=', date('Y-m-d') . ' 00:00:00')->
			sum('qty');

		$prd_ogfuel = DB::table('prd_ogfuel')->where("product_id", $fuel_prod_id)->first();

		$rec = DB::table('fuelmovement')->where('date', date('Y-m-d'))->
			where('ogfuel_id', $prd_ogfuel->id)->first();

		$book = 0;
		if (!empty($rec))
			$book = $rec->cforward - $qtysum + $rec->receipt;
	
		$book += $refund;
        DB::table('fuelmovement')->where('date', date('Y-m-d'))->
        where('ogfuel_id', $prd_ogfuel->id)->
		update([
			'book'	=> $book ?? 0,
            "sales" => $qtysum,
            "updated_at" => date('Y-m-d H:i:s'),
        ]);

        return true;
    }

    public function fuelmovementmaintable(Request $request)
    {

        $startDate = Carbon::now(); //returns current day
        $firstDay = $startDate->firstOfMonth();
        // return strtotime($firstDay)." ".strtotime($request->startmonth);
        $company = Company::first();
        $location = Location::first();
        $prd_ogfuel = DB::table('prd_ogfuel')->where("product_id", $request->fuel_prod_id)->first();
        $today = DB::table('fuelmovement')->
        where('ogfuel_id', $prd_ogfuel->id)->
        where('date', date('Y-m-d'))->first();
        if ($today === null) {
            $lastday = DB::table('fuelmovement')->
            where('ogfuel_id', $prd_ogfuel->id)->
            where('date', date('Y-m-d', strtotime(' -1 day')))->first();
            if (strtotime($request->startmonth) == strtotime(date('Y-m-d'))) {
                DB::table('fuelmovement')->insert([
                    "location_id" => $location->id,
                    "ogfuel_id" => $prd_ogfuel->id,
                    "franchisee_company_id" => $company->id,
                    "date" => date('Y-m-d'),
                    "cforward" => "0.00",
                    "sales" => "0.00",
                    "receipt" => "0.00",
                    "book" => "0.00",
                    "tank_dip" => "0.00",
                    "daily_variance" => "0.00",
                    "cumulative" => "0.00",
                    "percentage" => "0.00",
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s'),
                ]);
            } elseif ($lastday === null) {
                DB::table('fuelmovement')->insert([
                    "location_id" => $location->id,
                    "ogfuel_id" => $prd_ogfuel->id,
                    "franchisee_company_id" => $company->id,
                    "date" => date('Y-m-d'),
                    "cforward" => "0.00",
                    "sales" => "0.00",
                    "receipt" => "0.00",
                    "book" => "0.00",
                    "tank_dip" => "0.00",
                    "daily_variance" => "0.00",
                    "cumulative" => "0.00",
                    "percentage" => "0.00",
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s'),
                ]);
            } else {
                $percentage = 0;
                $book = $lastday->tank_dip;
                $daily_variance = 0 - $book;
                $cumulative = $daily_variance + $lastday->cumulative;
                if ($cumulative != 0) {
                    $percentage = $cumulative / $book * 100;
                }
                DB::table('fuelmovement')->insert([
                    "location_id" => $location->id,
                    "ogfuel_id" => $prd_ogfuel->id,
                    "franchisee_company_id" => $company->id,
                    "date" => date('Y-m-d'),
                    "cforward" => $lastday->tank_dip,
                    "sales" => "0.00",
                    "receipt" => "0.00",
                    "book" => $book,
                    "tank_dip" => "0.00",
                    "daily_variance" => $daily_variance,
                    "cumulative" => $cumulative,
                    "percentage" => $percentage,
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s'),
                ]);

            }
        }
        $lastday = DB::table('fuelmovement')->
        where('ogfuel_id', $prd_ogfuel->id)->
        where('date', date('Y-m-d', strtotime(' -1 day')))->first();
        // return $lastday;
        if ($lastday === null) {
            $datecheck = date('Y-m-d', strtotime(' -1 day'));
            $i = 1;
            $j = 1;
            // return $datecheck;
            while (strtotime($request->startmonth) <= strtotime($datecheck)) {
                $lastday = DB::table('fuelmovement')->
                where('ogfuel_id', $prd_ogfuel->id)->
                where('date', $datecheck)->first();

                $i++;
                if ($lastday === null) {
                    DB::table('fuelmovement')->insert([
                        "location_id" => $location->id,
                        "ogfuel_id" => $prd_ogfuel->id,
                        "franchisee_company_id" => $company->id,
                        "date" => $datecheck,
                        "cforward" => "0.00",
                        "sales" => "0.00",
                        "receipt" => "0.00",
                        "book" => "0.00",
                        "tank_dip" => "0.00",
                        "daily_variance" => "0.00",
                        "cumulative" => "0.00",
                        "percentage" => "0.00",
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s'),
                    ]);
                }
                $datecheck = date('Y-m-d', strtotime(' -' . $i . ' day'));
            }
            // return $i."j".$j;
        }


        $this->updateSalescolumn($request->fuel_prod_id);
        $product_id = $request->fuel_prod_id;
        $product_id = $request->fuel_prod_id;
        $startdate = $request->startmonth;
        $enddate = $request->endmonth;

        $data = DB::table('fuelmovement')->
        join('prd_ogfuel', 'fuelmovement.ogfuel_id', 'prd_ogfuel.id')->
        where('prd_ogfuel.product_id', $product_id)->
        whereBetween('date', [$startdate, $enddate])->
        select('fuelmovement.*', 'prd_ogfuel.product_id')->
        get()->map(function ($f) use ($product_id) {
			$yesterday_rec =  DB::table('fuelmovement')->
					join('prd_ogfuel', 'fuelmovement.ogfuel_id', 'prd_ogfuel.id')->
					where('prd_ogfuel.product_id', $product_id)->
					whereDate('date', date("Y-m-d", strtotime($f->date . ' -1day')))->
					select('fuelmovement.*', 'prd_ogfuel.product_id')->
					first();
 
            $f->receipt = DB::table('stockreportproduct')->
				leftjoin('stockreport', 'stockreport.id', 'stockreportproduct.stockreport_id')->
				where('stockreportproduct.product_id', $product_id)->
				whereYear("stockreport.created_at", date("Y", strtotime($f->date)))->
				whereMonth("stockreport.created_at", date("m", strtotime($f->date)))->
				whereDay("stockreport.created_at", date("d", strtotime($f->date)))->
				get()->sum('quantity');

			$f->sales = DB::table('fuel_receiptdetails')
                ->join('fuel_receiptproduct', 'fuel_receiptproduct.receipt_id', '=', 'fuel_receiptdetails.receipt_id')
                ->join('fuel_receipt', 'fuel_receiptproduct.receipt_id','=','fuel_receipt.id')
                ->where('fuel_receiptproduct.product_id', '=', $product_id)
                ->where('fuel_receipt.status', '!=', 'voided')
                ->whereDate('fuel_receiptdetails.created_at', date("Y-m-d", strtotime($f->date)))
                ->sum('fuel_receiptproduct.quantity');

			//Log::info("sales". $f->sales);

			$f->book += $f->receipt;

			$f->daily_variance = $f->tank_dip - $f->book;
 
			//$f->cumulative = ($yesterday_rec->tank_dip ?? 0) - ($yesterday_rec->book ?? 0) + $f->daily_variance;
			$f->cumulative = ($yesterday_rec->cumulative ?? 0 ) + $f->daily_variance;

			if( $f->book != 0)
				$f->percentage = abs(($f->cumulative / $f->book) * 100);
			/*
			\Log::info([
				
				'current tank_dip' => $f->tank_dip,
				'current book' => $f->book,
				'current daily_variance' => $f->daily_variance,

				'yesterday tank_dip' =>$yesterday_rec->tank_dip ?? 0,
				'yesterday book' => $yesterday_rec->book ?? 0 ,
				'yesterday daily_variance' => ($yesterday_rec->tank_dip ?? 0) - ($yesterday_rec->book ?? 0)

			]);*/
            return $f;
        });

        return $data;
    }

    public function showproductledgersReceipt(Request $request)
    {
        $product = DB::table('product')->where("id", $request->fuel_prod_id)->first();
        $location = Location::first();
        $data = collect();

        DB::table('receipt')->
        select('receipt.*', 'receiptproduct.quantity as quantity', 'receiptdetails.id as receiptdetails_id')->
        join('receiptproduct', 'receipt.id', 'receiptproduct.receipt_id')->
        leftJoin('receiptdetails', 'receipt.id', 'receiptdetails.receipt_id')->
        where("receiptproduct.product_id", $request->product_id)->get()->map(function ($product) use ($data) {
            $packet = collect();
            $packet->id = $product->id;
            $packet->status = $product->status;
            $packet->systemid = $product->systemid;
            $packet->quantity = $product->quantity;
            $packet->created_at = $product->created_at;
            $packet->voided_at = $product->voided_at;
            $packet->doc_type = "Cash Sales";
            $data->push($packet);
        });

        DB::table('stockreportproduct')->
        leftjoin('stockreport', 'stockreport.id', 'stockreportproduct.stockreport_id')->
        where('stockreportproduct.product_id', $product->id)->
        whereYear("stockreport.created_at", date("Y", strtotime($request->date)))->
        whereMonth("stockreport.created_at", date("m", strtotime($request->date)))->
        whereDay("stockreport.created_at", date("d", strtotime($request->date)))->
        orderBy('stockreport.id','desc')->
        get()->map(function ($product) use ($data) {
            $packet = collect();
            $packet->id = $product->id;
            $packet->status = $product->status;
            $packet->systemid = $product->systemid;
            $packet->quantity = $product->quantity;
            $packet->created_at = $product->created_at;
            $packet->voided_at = $product->voided_at ?? "";
            $packet->doc_type = ucfirst($product->type);
            $packet->staff = DB::table('users')->find($product->creator_user_id)->fullname;
            $data->push($packet);
        });

        return view("fuel_movement.productledger_receipt", compact("location", "product", "data"));
    }

    function showOnlyProductLedgersReceipt(Request $request){
        $product = DB::table('product')->where("id", $request->fuel_prod_id)->first();
        $location = Location::first();
        $data = collect();

        DB::table('fuel_receipt')->
        select('fuel_receipt.*', 'fuel_receiptproduct.quantity as quantity', 'fuel_receiptdetails.id as receiptdetails_id')->
        join('fuel_receiptproduct', 'fuel_receipt.id', 'fuel_receiptproduct.receipt_id')->
        leftJoin('fuel_receiptdetails', 'fuel_receipt.id', 'fuel_receiptdetails.receipt_id')->
        where("fuel_receiptproduct.product_id", $request->product_id)->get()->map(function ($product) use ($data) {
            $packet = collect();
            $packet->id = $product->id;
            $packet->status = $product->status;
            $packet->systemid = $product->systemid;
            $packet->quantity = $product->quantity;
            $packet->created_at = $product->created_at;
            $packet->voided_at = $product->voided_at;
            $packet->doc_type = "Cash Sales";
            $data->push($packet);
        });

        DB::table('stockreportproduct')->
        leftjoin('stockreport', 'stockreport.id', 'stockreportproduct.stockreport_id')->
        where('stockreportproduct.product_id', $product->id)->
        whereYear("stockreport.created_at", date("Y", strtotime($request->date)))->
        whereMonth("stockreport.created_at", date("m", strtotime($request->date)))->
        whereDay("stockreport.created_at", date("d", strtotime($request->date)))->
        orderBy('stockreport.id','desc')->
        get()->map(function ($product) use ($data) {
            $packet = collect();
            $packet->id = $product->id;
            $packet->status = $product->status;
            $packet->systemid = $product->systemid;
            $packet->quantity = $product->quantity;
            $packet->created_at = $product->created_at;
            $packet->voided_at = $product->voided_at ?? "";
            $packet->doc_type = ucfirst($product->type);
            $packet->staff = DB::table('users')->find($product->creator_user_id)->fullname;
            $data->push($packet);
        });

        return view("fuel_movement.productledger_receipt", compact("location", "product", "data"));
    }

    function dataTable($id, $date){
        $receipts = DB::table('fuel_receipt')->
        select('fuel_receipt.*', 'fuel_receiptproduct.quantity as quantity', 'fuel_receiptdetails.id as receiptdetails_id',
            'receiptrefund.qty','receiptrefund.refund_amount')->
        join('fuel_receiptproduct', 'fuel_receipt.id', 'fuel_receiptproduct.receipt_id')
            ->whereDate('fuel_receipt.created_at', '=', date('Y-m-d', strtotime($date)))
            ->leftJoin('fuel_receiptdetails', 'fuel_receipt.id', 'fuel_receiptdetails.receipt_id')
            ->leftJoin('receiptrefund','receiptrefund.receipt_id', 'fuel_receipt.id')
            ->where("fuel_receiptproduct.product_id", $id)->orderBy('fuel_receipt.id','desc')->get();

        return Datatables::of($receipts)->
        addIndexColumn()->
        addColumn('date', function ($data) {
            $created_at = Carbon::parse($data->created_at)->format('dMy H:i:s');
            return <<<EOD
					$created_at
EOD;
        })->
        addColumn('systemid', function ($data) {
            $systemid = !empty($data->systemid) ? '<a href="javascript:void(0)"  style="text-decoration:none;" onclick="showReceipt(' . $data->id . ')" > ' . $data->systemid . '</a>' : 'Receipt ID';
            return <<<EOD
					$systemid
EOD;
        })->
        addColumn('type', function ($data) {
            $type = "Cash Sales";
            return <<<EOD
					$type
EOD;
        })->
        addColumn('location', function ($data) {
            $location = Location::first();
            return <<<EOD
					$location->name
EOD;
        })->
        addColumn('qty', function ($data) {
            $qty =$data->quantity;
            if ($data->status=="voided")
            {
                $qty = "0.00";
            }
            return <<<EOD
					$qty;
EOD;
        })->

        addColumn('status_color', ' ')->
        editColumn('status_color', function ($row) {
            $status = "none";
            if ($row->status=="voided")
            {
                $status = "red";
            }
            if ($row->status=="refunded")
            {
                $status = "#ff7e30";
            }
            return $status;

        })->
        rawColumns(['action'])->
        escapeColumns([])->
        make(true);
    }


}
