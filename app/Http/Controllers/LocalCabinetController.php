<?php

namespace App\Http\Controllers;

use App\Models\CarPark;
use App\Models\ReceiptDetails;
use App\Models\ReceiptRefund;
use Log;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Location;
use App\Models\Receipt;
use App\Models\Terminal;
use Illuminate\Support\Facades\Auth;
use \App\Classes\SystemID;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\DNS2D;
use Carbon\Carbon;
use App\Http\Controllers\SetupController;
use Yajra\DataTables\DataTables;

class LocalCabinetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $user = Auth::user();
        $company = Company::first();

        $this->check_personal_shift();
        $client_ip = request()->ip();
        $terminal = DB::table('terminal')->
        where('client_ip', $client_ip)->first();

        $location = Location::first();


        $dataReceipt = DB::table('receipt')->orderBy("id", "desc")->
			get();
		
		$dataReceipt_2 = DB::table('cstore_receipt')->orderBy("id", "desc")->
			get();
        
        $dataReceipt = $dataReceipt->merge($dataReceipt_2);

        $dataReceipt_3 = DB::table('fuel_receipt')->orderBy("id", "desc")->
			get();

		$dataReceipt = $dataReceipt->merge($dataReceipt_3);

		$dataReceipt = $dataReceipt->groupBy(function ($date) {
				return Carbon::parse($date->created_at)->
				format('d'); // grouping by months
			});

        $receipt = collect();

        $dataReceipt->map(function ($z) use ($receipt) {
            $packet = collect();
            $packet->date = date('dMy', strtotime($z->min('created_at')));
            $packet->day = date('dMY', strtotime($z->min('created_at')));
            $packet->ev = 0;
            $packet->opt = 0;
            $packet->created_at = strtotime($packet->date);
            $packet->shift = DB::table('loginout')->
            whereDate('created_at', date("Y-m-d",
                strtotime($z->min('created_at'))))->get()->count();
            $packet->count_receipt_fuel = DB::table('fuel_receipt')->
            selectRaw('fuel_receipt.* , fuel_receiptdetails.* , fuel_receiptproduct.* ,receiptfilled.* , fuel_receipt.id as receipt_id ')->
            join('fuel_receiptdetails', 'fuel_receiptdetails.receipt_id', 'fuel_receipt.id')->
            join('fuel_receiptproduct', 'fuel_receiptproduct.receipt_id', 'fuel_receipt.id')->
            leftjoin('authreceipt', 'authreceipt.receipt_id', 'fuel_receipt.id')->
            leftjoin('receiptfilled', 'receiptfilled.auth_systemid', 'authreceipt.auth_systemid')->
            whereNull('fuel_receipt.deleted_at')->
            whereDate('fuel_receipt.created_at', date('Y-m-d', strtotime($packet->date)))->get()->count();

			$packet->count_receipt_cstore = DB::table('cstore_receipt')->
				whereDate('created_at', date('Y-m-d', strtotime($packet->date)))->get()->count();

            $receipt->push($packet);
        });
	
		$receipt = $receipt->sortBy('date', SORT_REGULAR, true);
		/* This is for the new split C-Store receipt */
		$cstore = collect();

        return view('local_cabinet.local_cabinet', compact(
            'company', 'terminal', 'location', 'user', 'cstore', 'receipt'));
    }

	public function cstore_receipt_landing(Request $request) {
		
		$client_ip = request()->ip();
        $terminal = DB::table('terminal')->
        where('client_ip', $client_ip)->first();

        $location = Location::first();

		$receipt = DB::table('cstore_receipt')->whereDay('created_at', '=',
            date('d', strtotime($request->date)))->
			whereMonth('created_at', '=', date('m', strtotime($request->date)))->
			whereYear('created_at', '=', date('Y', strtotime($request->date)))->
			orderBy("id", "desc")->get();
	   
		$receipt->map(function ($z) {
            $z->is_refunded = !empty(DB::table('cstore_receiptrefund')->
            where('cstore_receipt_id', $z->id)->first());
		});

   		return view('cstore.cstore_receipt', compact('receipt', 'terminal', 'location'));	
	}

    public function checkNric(Request $request){

        $nric = $request->nric;
        $data  = array('nric'=>$nric);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://ocosystem/api/checkNric",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $response =  	$err;
        } 
        
        return response()->json($response, 200);
    }

    public function receiptlistTable(Request $request)
    {
        $receipt = Receipt::whereDay('created_at', '=',
            date('d', strtotime($request->date)))->
        whereMonth('created_at', '=', date('m', strtotime($request->date)))->
        whereYear('created_at', '=', date('Y', strtotime($request->date)))->
        orderBy("id", "desc")->get();

        $receipt->map(function ($z) {
            $z->is_refunded = !empty(DB::table('receiptrefund')->
            where('receipt_id', $z->id)->first());

            $z->filled = DB::table('receiptfilled')->
				join('authreceipt', 'authreceipt.auth_systemid', 'receiptfilled.auth_systemid')->
                where('authreceipt.receipt_id', $z->id)->
				select('receiptfilled.*')->first()->filled ?? '0.00';

            $z->fuel = (float) DB::table('receipt')->
            join('receiptproduct', 'receiptproduct.receipt_id', 'receipt.id')->
            join('product', 'product.id', 'receiptproduct.product_id')->
            where([
                'product.ptype' => 'oilgas',
                'receipt.id' => $z->id
            ])->
            select('receiptproduct.*')->
            get()->reduce(function ($carry, $rec) {
                return $carry + (($rec->price / 100) * $rec->quantity);
            });


            $z->rounding = number_format((DB::table('receiptdetails')->
                    where('receipt_id', $z->id)->
                    first()->
                    rounding ?? 0) / 100, 2);

            $z->refund = ($z->fuel) - $z->filled;
        });
        return view('local_cabinet.receipt_table', compact('receipt'));
    }


    public function eodSummaryPopup(Request $request)
    {
        $eod_date = $request->eod_date;
        if ($request->eod_date) {
            $date_eod = date_create($request->eod_date);
            $date_eod = date_format($date_eod, 'Y-m-d');
        } else {
            $date_eod = date('Y-m-d');
        }

        $user = Auth::user();
        $location = Location::first();
        $todaydate = date('Y-m-d');

        $client_ip = request()->ip();
        $terminal = DB::table('terminal')->
        where('client_ip', $client_ip)->first();

        $eoddetailsdata = DB::table('eoddetails')->
        whereDate('startdate', '=', $date_eod)->first();

        $receipts = Receipt::with('receiptdetails')->
        whereDate('receipt.created_at', '=', $date_eod)->
        where('receipt.status', 'voided')->
        get();

        $reverseAmount = 0;
        $reverseCash = 0;
        $reverseCard = 0;
        $reverseWallet = 0;
        $reverseTax = 0;
        $reverseRound = 0;

        foreach ($receipts as $receipt) {
            if ($receipt->payment_type == 'creditcard') {
                $reverseCard += $receipt->receiptdetails->creditcard;
            } elseif ($receipt->payment_type == 'wallet') {
                $reverseWallet += $receipt->receiptdetails->wallet;
            } else {
                $reverseCash += $receipt->receiptdetails->cash_received - $receipt->receiptdetails->change;
            }
            $reverseTax += $receipt->receiptdetails->sst;
            $reverseRound += $receipt->receiptdetails->rounding;
        }

        $reverseAmount += ($reverseCard + $reverseCash + $reverseWallet) - $reverseTax - $reverseRound;

        $refund_data = DB::table('receiptrefund')->
        join('receipt', 'receipt.id', 'receiptrefund.receipt_id')->
        whereDate('receipt.created_at', '=', $date_eod)->get()->sum('refund_amount');

        \Log::info(['refund total' => $refund_data]);
        $refund_p_amount = $refund_data;
        $tax_percent = ($terminal->tax_percent ?? 6);
        $refund_data = ($refund_data) / (1 + ($tax_percent / 100));
        $refund_sst = $refund_p_amount - $refund_data;;
        $refund_round = $refund_data + $refund_sst;
        $refund_round = (float)number_format($this->round_amount($refund_round) / 100, 2);

        \Log::info([
            'sst' => $refund_sst,
            'tax percent' => ($terminal->tax_percent ?? 6),
            'refund_round' => $refund_round,
            'amount' => $refund_data
        ]);

        $sst_tax = ($eoddetailsdata->sst ?? 0) - $reverseTax;
        $round = ($eoddetailsdata->rounding ?? 0) - $reverseRound;

        $user = Auth::user();
        $company = Company::first();
        $location = Location::first();
        return view('local_cabinet.eod_summarylist', compact('round',
            'company', 'terminal', 'location', 'user',
            'eoddetailsdata', 'reverseAmount', 'reverseTax',
            'reverseCash', 'reverseCard', 'eod_date', 'refund_data',
            'refund_sst', 'refund_round', 'reverseWallet'));
    }


    public function round_amount($num)
    {
        $num = round($num, 2);
        $split = explode('.', $num);
        if (is_array($split)) {
            $whole = $split[0];
            $dec = $split[1] ?? 0;
            $round_fig = substr($dec, 1, 1);
            if ($round_fig <= 2 && $round_fig > 0) {
                return (int)-($round_fig);
            } else if ($round_fig < 5 && $round_fig > 2) {
                $res = 5 - $round_fig;
                return (int)("$res");
            } else if ($round_fig < 8 && $round_fig > 5) {
                $res = $round_fig - 5;
                return (int)-("$res");
            } else if ($round_fig <= 9 && $round_fig >= 8) {
                $res = 10 - $round_fig;
                return (int)("$res");
            }
            return 0;
        } else {
            return 0;
        }
    }


    public function eodReceiptPopup(Request $request)
    {
        $receipt = Receipt::find($request->id);
        $user = User::where('id', $receipt->staff_user_id)->first();
        $company = Company::where('id', $receipt->company_id)->first();
        $terminal = Terminal::where('id', $receipt->terminal_id)->first();
        $location = Location::first();
        $receiptproduct = DB::table('receiptproduct')->
        where('receipt_id', $receipt->id)->get();

        $receiptdetails = DB::table('receiptdetails')->
        where('receipt_id', $receipt->id)->first();
        $milon = new DNS2D;
        $qrcode = $milon->getBarcodePNG($receipt->systemid, "QRCODE");

        $ref = DB::table('receiptrefund')->
        join('users', 'receiptrefund.staff_user_id', '=', 'users.id')->
        where('receipt_id', $receipt->id)->
        select('receiptrefund.*', 'users.fullname as name', 'users.systemid as systemid')->
        first();

        if (!empty($ref)) {
            $ref->refund_amount += $this->round_amount($ref->refund_amount) / 100;
        }

        $refund = '';
        if ($ref) {
            $refund = $ref;
            return view('local_cabinet.receipt', compact(
                'company', 'terminal', 'location',
                'user', 'receipt', 'receiptproduct', 'receiptdetails',
                'qrcode', 'refund'));

        } else {
            return view('local_cabinet.receipt', compact(
                'company', 'terminal', 'location',
                'user', 'receipt', 'receiptproduct',
                'receiptdetails', 'qrcode', 'refund'));
        }
    }


    public function eodReceiptVoid(Request $request)
    {
        $user = Auth::user();
        $receipt = Receipt::find($request->receiptid);
        $receipt->voided_at = now();//$request->voitdatetime;
        $receipt->void_user_id = $user->id;
        $receipt->void_reason = $request->reason_void;
        $receipt->status = "voided";
        $receipt->save();
        return true;
    }


    public function ReceiptRefund(Request $request)
    {
        Log::debug('ReceiptRefund: receipt_id=' . $request->receipt_id);

        $receipt = DB::table('receipt')->
        find($request->receipt_id);

        if ($receipt->status == 'voided')
            return 'VOID';

        $receiptproduct = DB::table('receiptproduct')->
        join('prd_ogfuel', 'prd_ogfuel.product_id', 'receiptproduct.product_id')->
        where('receiptproduct.receipt_id', $request->receipt_id)->
        select('prd_ogfuel.id as og_id', 'receiptproduct.*')->
        first();

        $price = $receiptproduct->price / 100;

        Log::debug('ReceiptRefund: price     =' . $price);

        $refund_amt = $request->filled;

        Log::debug('ReceiptRefund: refund_amt=' . $refund_amt);
        //Log::debug('ReceiptRefund: rounding  ='.$this->round_amount($refund_amt)/100);

        //$refund_amt +=  $this->round_amount($refund_amt)/100;

        //Log::debug('ReceiptRefund: refund_amt='.$refund_amt.' (AFTER rounding)');

        if ($refund_amt <= 0)
            $refund_amt = 0;

        $refund_qty = $refund_amt / $price;

        Log::debug('ReceiptRefund: refund_qty=' . $refund_qty);

        try {
            DB::table('receiptrefund')->insert([
                'receipt_id' => $request->receipt_id,
                'staff_user_id' => $request->user()->id,
                'refund_amount' => $refund_amt,
                'qty' => $refund_qty ?? 0,
                "created_at" => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        } catch (Exception $e) {
            Log::error([
                'Message' => $e->getMessage(),
                'File' => $e->getFile(),
                'Line' => $e->getLine(),
            ]);
            //do nothing
        }
        return true;
    }

	public function ReceiptRefund_cstore(Request $request) {
		try {
		
			DB::table('cstore_receiptrefund')->insert([
                'cstore_receipt_id' => $request->receipt_id,
                'staff_user_id' => $request->user()->id,
                'refund_amount' => $request->amount,
				'description'	=> $request->description,
                "created_at" => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

		} catch (Exception $e) {
            Log::error([
                'Message' => $e->getMessage(),
                'File' => $e->getFile(),
                'Line' => $e->getLine(),
            ]);
        }

	}

    public function calculate_Refund($receipt_id)
    {
        $receiptproduct = DB::table('receiptproduct')->
        join('prd_ogfuel', 'prd_ogfuel.product_id', 'receiptproduct.product_id')->
        where('receiptproduct.receipt_id', $receipt_id)->
        select('prd_ogfuel.id as og_id', 'receiptproduct.*')->
        first();

        $volume_filled = DB::table('receiptfilled')->
			join('authreceipt', 'authreceipt.auth_systemid', 'receiptfilled.auth_systemid')->
            where('authreceipt.receipt_id', $receipt_id)->select('receiptfilled.*')->first()->filled ?? 0;

        if ($volume_filled < $receiptproduct->quantity) {
            $refund_qty = $receiptproduct->quantity - $volume_filled;
        } else {
            return "0.00";
        }

        $price = $receiptproduct->price / 100;

        $refund_amt = $price * $refund_qty;

        return $refund_amt;
    }


	function ReceiptCreate(Request $request)
    {
        try {
           	// $products = $request->products;
            $client_ip = request()->ip();

            $terminal = DB::table('terminal')->
				where('client_ip', $client_ip)->first();

            $user = Auth::user();
            $company = Company::first();
            $location = Location::first();
            $systemid = Systemid::receipt_system_id($terminal->id);
			$pump_hardware = DB::table('local_pump')->
        		where("pump_no", $request->pump_no)->first();


            $receipt = new Receipt();
            $receipt->systemid = $systemid;

            if ($request->payment_type == "card") {
                $receipt->payment_type = "creditcard";
                $receipt->creditcard_no = $request->creditcard_no ?? 0;
                $receipt->cash_received = ($request->cash_received ?? 0) * 100;
			} elseif ($request->payment_type == 'wallet') {
				$receipt->payment_type = "wallet";
				$receipt->cash_received = ($request->cash_received ?? 0) * 100;
			} else {
                $receipt->payment_type = $request->payment_type;
                $receipt->cash_received = ($request->cash_received ?? 0) * 100;
                $receipt->cash_change = ($request->change_amount ?? 0) * 100;
            }
			
			$receipt->company_name = $company->name;
			$receipt->gst_vat_sst = $company->gst_vat_sst;
			$receipt->business_reg_no = $company->business_reg_no;

            $receipt->service_tax = $terminal->tax_percent;
            $receipt->terminal_id = $terminal->id;
            $receipt->mode = $terminal->mode;

            $receipt->staff_user_id = $user->id;
            $receipt->company_id = $company->id;
            $receipt->receipt_logo = $company->corporate_logo;
            $receipt->receipt_address = $company->office_address;
            //	$receipt->currency = "NULL";//$company->currency;

            $receipt->status = "active";
            $receipt->remark = "NULL";
            $receipt->transacted = "pos";

			$receipt->pump_id = $pump_hardware->id;
			$receipt->pump_no = $request->pump_no;

			$receipt->transacted = "pos";
            $receipt->save();
		
			try {
				DB::table('authreceipt')->where([
					"auth_systemid" => $request->auth_id
				])->update([
					"receipt_id"	=> $receipt->id,
					"updated_at"	=> now()
				]);
			} catch (\Exception $e) {
				\Log::info( "authreceipt failed : " . $e->getMessage());
			}

			
			$receiptproductsdiscount = 0;
			
			$receiptproduct_id = DB::table('receiptproduct')->insertGetId([
				"receipt_id" => $receipt->id,
				"product_id" => $request->product_id,
				"name" => $request->product_name,
				"quantity" => $request->product_qty,
				"price" => $request->product_price * 100,
				"discount_pct" => 0,
				"discount" => 0,
				"created_at" => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			]);

		
			$amount = (float) number_format($request->cal_item_amount);
			$price = (float) number_format($request->product_price);
			$sst = (float) number_format($request->cal_sst);
			$total_amount = (float) number_format($request->cal_total);
			$rounding = (float) number_format($request->cal_rounding);
			
			DB::table('itemdetails')->insert([
				"receiptproduct_id" => $receiptproduct_id,
				"amount" => $amount * 100,
				"rounding" => $rounding,
				"price" => $price * 100,
				"sst" => $sst * 100,
				"discount" => 0,
				"created_at" => $receipt->created_at,
				'updated_at' => $receipt->created_at,
			]);

			$cash_received = 0;
			$cash_change = 0;
			$creditcard = 0;
			if ($receipt->payment_type == "cash") {
				$cash_received = $request->cash_received;
			} elseif ($receipt->payment_type == "wallet") {
				$wallet =	$request->cal_total; 
			} else {
				$creditcard = $request->cal_total;
			}
		   
			DB::table('receiptdetails')->insert([
				"receipt_id" => $receipt->id,
				"total" => $request->cal_total * 100,
				"rounding" => $request->cal_rounding * 100,
				"item_amount" => $request->cal_item_amount * 100,
				"sst" => $request->cal_sst * 100,
				"discount" => $receiptproductsdiscount * 100,
				"cash_received" => $cash_received * 100,
				"change" => $request->change_amount * 100,
				"creditcard" => $creditcard * 100,
				"wallet" => ($wallet ?? 0) * 100,
				"created_at" => $receipt->created_at,
				'updated_at' => $receipt->created_at,
			]);


            $brancheoddata = DB::table('brancheod')->
            whereDate('created_at', '=', date('Y-m-d'))->first();

            if (empty($brancheoddata)) {
                $brancheod = DB::table('brancheod')->insertGetId([
                    "eod_presser_user_id" => $user->id,
                    "location_id" => $location->id,
                    "terminal_id" => $terminal->id,
                    "created_at" => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                DB::table('eoddetails')->insert([
                    "eod_id" => $brancheod,
                    "startdate" => date('Y-m-d'),
                    "total_amount" => DB::table('receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('total'),
                    "rounding" => DB::table('receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('rounding'),
                    "sales" => DB::table('receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('item_amount'),
                    "sst" => DB::table('receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('sst'),
                    "discount" => DB::table('itemdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('discount'),
                    "cash" => DB::table('receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('cash_received'),
                    "cash_change" => DB::table('receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('change'),
                    "creditcard" => DB::table('receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('creditcard'),
                    "wallet" => DB::table('receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('wallet'),
                    "created_at" => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            } else {
                DB::table('eoddetails')->
                where('eod_id', $brancheoddata->id)->update([
                    "total_amount" => DB::table('receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('total'),
                    "rounding" => DB::table('receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('rounding'),
                    "sales" => DB::table('receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('item_amount'),
                    "sst" => DB::table('receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('sst'),
                    "discount" => DB::table('itemdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('discount'),
                    "cash" => DB::table('receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('cash_received'),
                    "cash_change" => DB::table('receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('change'),
                    "wallet" => DB::table('receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('wallet'),
                    "creditcard" => DB::table('receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('creditcard'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            $this->check_personal_shift();
            $receipt = DB::table('receipt')->
				where('id', $receipt->id)->first();

            $receipt_details = DB::table('receiptdetails')->
				where('receipt_id', $receipt->id)->first();

            $receipt_product = DB::table('receiptproduct')->
				where('receipt_id', $receipt->id)->first();

            $user = DB::table('users')->
				where('id', $receipt->staff_user_id)->first();

            $itemdetails = DB::table('itemdetails')->
				where('receiptproduct_id', $receipt_product->id)->first();

            $payload = ['user' => $user, 'receipt' => $receipt,
                'receipt_details' => $receipt_details,
                'receipt_product' => $receipt_product,
                'itemdetails' => $itemdetails];

            $load = json_encode($payload);

            //API Call
            $setup = new SetupController;
            $response = $setup->updateReceiptatMotherShip($load);
            Log::debug('updateReceiptatMotherShip=' . json_encode($response));


            return $receipt->id;

        } catch (\Exception $e) {
            Log::info([
                'Error' => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);

            return $e;
        }
    }


    function ReceiptCreateCStore(Request $request)
    {
        try {
            $products = $request->products;
            $client_ip = request()->ip();

            $terminal = DB::table('terminal')->where('client_ip', $client_ip)->first();
            $user = Auth::user();
            $company = Company::first();
            $location = Location::first();
            $systemid = Systemid::receipt_system_id($terminal->id);

			$receipt = [];
            $receipt['systemid'] = $systemid;

            if ($request->payment_type == "card") {
                $receipt['payment_type'] = "creditcard";
                $receipt['creditcard_no'] = $request->creditcard_no ?? 0;
                $receipt['cash_received'] = ($request->cash_received ?? 0) * 100;
			} elseif ($request->payment_type == 'wallet') {
				$receipt['payment_type'] = "wallet";
				$receipt['cash_received'] = ($request->cash_received ?? 0) * 100;
			} else {
                $receipt['payment_type'] = $request->payment_type;
                $receipt['cash_received'] = ($request->cash_received ?? 0) * 100;
                $receipt['cash_change'] = ($request->change_amount ?? 0) * 100;
            }
			
			$receipt['company_name'] = $company->name;
			$receipt['gst_vat_sst'] = $company->gst_vat_sst;
			$receipt['business_reg_no'] = $company->business_reg_no;

            $receipt['service_tax'] = $terminal->tax_percent;
            $receipt['terminal_id'] = $terminal->id;
            $receipt['mode'] = $terminal->mode;

            $receipt['staff_user_id'] = $user->id;
            $receipt['company_id'] = $company->id;
            $receipt['receipt_logo'] = $company->corporate_logo;
            $receipt['receipt_address'] = $company->office_address;
			
			//	$receipt->currency = "NULL";//$company->currency;

            $receipt['status'] = "active";
            $receipt['remark'] = "NULL";
            $receipt['transacted'] = "pos";

			$receipt['created_at'] = $receipt['updated_at'] = now();
			$receipt = DB::table('cstore_receipt')->insertGetId($receipt);
			$receipt = DB::table('cstore_receipt')->find($receipt);
			
			$receiptproductsdiscount = 0;

			foreach ($products as $p) {
				//auto siso
				$exisitingQty = (int) app("App\Http\Controllers\CentralStockMgmtController")->
					qtyAvailable($p['product_id']);
				$req_qty = (int) $p['qty'];

				if ($exisitingQty < $req_qty)
					app("App\Http\Controllers\CentralStockMgmtController")->
						autoStockIn($p['product_id'], ($req_qty - $exisitingQty));
				
				$receiptproduct_id = DB::table('cstore_receiptproduct')->insertGetId([
					"receipt_id" => $receipt->id,
					"product_id" => $p['product_id'],
					"name" => $p['name'],
					"quantity" => $p['qty'],
					"price" => $p['price'] * 100,
					"discount_pct" => $p['discount'],
					"discount" => $p['discount_amount'] * 100,
					"created_at" => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s'),
				]);

				$receiptproductsdiscount += $p['discount_amount'];

				$receiptproduct = DB::table('cstore_receiptproduct')->
					where('id', $receiptproduct_id)->first();

				$amount = (float) number_format($p['item_amount'],2);
				$price = (float) number_format($p['price'],2);
				$sst = (float) number_format($p['sst'],2);
				$total_amount = (float) number_format($p['total_amount'],2);

				//$item_amount = ($amount) / (1 + ($terminal->tax_percent / 100));
				//$item_amount = round($item_amount, 2);
				//$sst = abs($amount - $item_amount);

				$rounding = $this->round_amount($total_amount);

				DB::table('cstore_itemdetails')->insert([
					"receiptproduct_id" => $receiptproduct->id,
					"amount" => $amount * 100,
					"rounding" => $rounding,
					"price" => $price * 100,
					"sst" => $sst * 100,
					"discount" => $receiptproduct->discount_pct,
					"created_at" => $receipt->created_at,
					'updated_at' => $receipt->created_at,
				]);
			}

			//$receiptproductsdiscount = 0;
			$cash_received = 0;
			$cash_change = 0;
			$creditcard = 0;
			if ($receipt->payment_type == "cash") {
				$cash_received = $request->cash_received;
			} elseif ($receipt->payment_type == "wallet") {
				$wallet =	$request->cal_total; 
			} else {
				$creditcard = $request->cal_total;
			}
		   
			DB::table('cstore_receiptdetails')->insert([
				"receipt_id" => $receipt->id,
				"total" => $request->cal_total * 100,
				"rounding" => $request->cal_rounding * 100,
				"item_amount" => $request->cal_item_amount * 100,
				"sst" => $request->cal_sst * 100,
				"discount" => $receiptproductsdiscount * 100,
				"cash_received" => $cash_received * 100,
				"change" => $request->change_amount * 100,
				"creditcard" => $creditcard * 100,
				"wallet" => ($wallet ?? 0) * 100,
				"created_at" => $receipt->created_at,
				'updated_at' => $receipt->created_at,
			]);

			$this->updateEOD();

			$this->check_personal_shift();
			/*
            $receipt = DB::table('receipt')->where('id', $receipt->id)->first();
            $receipt_details = DB::table('receiptdetails')->where('receipt_id', $receipt->id)->first();
            $receipt_product = DB::table('receiptproduct')->where('receipt_id', $receipt->id)->first();
            $user = DB::table('users')->where('id', $receipt->staff_user_id)->first();
            $itemdetails = DB::table('itemdetails')->where('receiptproduct_id', $receipt_product->id)->first();

            $payload = ['user' => $user, 'receipt' => $receipt,
                'receipt_details' => $receipt_details,
                'receipt_product' => $receipt_product,
                'itemdetails' => $itemdetails];

            $load = json_encode($payload);

            //API Call
            $setup = new SetupController;
            $response = $setup->updateReceiptatMotherShip($load);
            Log::debug('updateReceiptatMotherShip=' . json_encode($response));
			*/

            return $receipt->id;

        } catch (\Exception $e) {
            \Log::info([
                'Error' => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);

            return $e;
        }
    }

	public function cstore_receipt(Request $request)
    {
        $receipt = DB::table('cstore_receipt')->find($request->id);
        $user = User::where('id', $receipt->staff_user_id)->first();
        $company = Company::where('id', $receipt->company_id)->first();
        $terminal = Terminal::where('id', $receipt->terminal_id)->first();
        $location = Location::first();
        $receiptproduct = DB::table('cstore_receiptproduct')->
        where('receipt_id', $receipt->id)->get();

        $receiptdetails = DB::table('cstore_receiptdetails')->
        where('receipt_id', $receipt->id)->first();
        $milon = new DNS2D;
        $qrcode = $milon->getBarcodePNG($receipt->systemid, "QRCODE");

        $ref = DB::table('cstore_receiptrefund')->
			join('users', 'cstore_receiptrefund.staff_user_id', '=', 'users.id')->
			where('cstore_receiptrefund.cstore_receipt_id', $receipt->id)->
			select('cstore_receiptrefund.*', 'users.fullname as name', 'users.systemid as systemid')->
			first();

        if (!empty($ref)) {
            $ref->refund_amount += $this->round_amount($ref->refund_amount) / 100;
        }

        $refund = '';
        if ($ref) {
            $refund = $ref;
            return view('cstore.receipt', compact(
                'company', 'terminal', 'location',
                'user', 'receipt', 'receiptproduct', 'receiptdetails',
                'qrcode', 'refund'));

        } else {
            return view('cstore.receipt', compact(
                'company', 'terminal', 'location',
                'user', 'receipt', 'receiptproduct',
                'receiptdetails', 'qrcode', 'refund'));
        }
    }


	function updateEOD() {
		try {
		 
            $user = Auth::user();
            $company = Company::first();
            $location = Location::first();
			$client_ip = request()->ip();
            $terminal = DB::table('terminal')->where('client_ip', $client_ip)->first();


			$brancheoddata = DB::table('eoddetails')->
            whereDate('created_at', '=', date('Y-m-d'))->first();

			$receipt_details_cstore = DB::table('cstore_receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->get();

			$cstore_itemdetails = DB::table('cstore_itemdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->get();

            if (empty($brancheoddata)) {
                $brancheod = DB::table('brancheod')->insertGetId([
                    "eod_presser_user_id" => $user->id,
                    "location_id" => $location->id,
                    "terminal_id" => $terminal->id,
                    "created_at" => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                DB::table('eoddetails')->insert([
                    "eod_id" => $brancheod,
                    "startdate" => date('Y-m-d'),
                    "total_amount" => $receipt_details_cstore->sum('total'),
                    "rounding" => $receipt_details_cstore->sum('rounding'),
                    "sales" => $receipt_details_cstore->sum('item_amount'),
                    "sst" => $receipt_details_cstore->sum('sst'),
                    "discount" => $cstore_itemdetails->sum('discount'),
                    "cash" => $receipt_details_cstore->sum('cash_received'),
                    "cash_change" => $receipt_details_cstore->sum('change'),
                    "creditcard" => $receipt_details_cstore->sum('creditcard'),
                    "wallet" => $receipt_details_cstore->sum('wallet'),
                    "created_at" => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            } else {
                DB::table('eoddetails')->
                where('eod_id', $brancheoddata->id)->update([
                    "total_amount" => $receipt_details_cstore->sum('total'),
                    "rounding" => $receipt_details_cstore->sum('rounding'),
                    "sales" => $receipt_details_cstore->sum('item_amount'),
                    "sst" => $receipt_details_cstore->sum('sst'),
                    "discount" => $cstore_itemdetails->sum('discount'),
                    "cash" => $receipt_details_cstore->sum('cash_received'),
                    "cash_change" => $receipt_details_cstore->sum('change'),
                    "wallet" => $receipt_details_cstore->sum('wallet'),
                    "creditcard" => $receipt_details_cstore->sum('creditcard'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
		
            $receipt = DB::table('receipt')->where('id', $receipt->id)->first();
            $receipt_details = DB::table('receiptdetails')->where('receipt_id', $receipt->id)->first();
            $receipt_product = DB::table('receiptproduct')->where('receipt_id', $receipt->id)->first();
            $user = DB::table('users')->where('id', $receipt->staff_user_id)->first();
            $itemdetails = DB::table('itemdetails')->where('receiptproduct_id', $receipt_product->id)->first();

            $payload = ['user' => $user, 'receipt' => $receipt,
                'receipt_details' => $receipt_details,
                'receipt_product' => $receipt_product,
                'itemdetails' => $itemdetails];

            $load = json_encode($payload);

            //API Call
            $setup = new SetupController;
            $response = $setup->updateReceiptatMotherShip($load);
            Log::debug('updateReceiptatMotherShip=' . json_encode($response));


            return $receipt->id;

        } catch (\Exception $e) {
            \Log::info([
                'Error' => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);

            return $e;
        }
    }


    function pssReceiptPopup()
    {
        $user = Auth::user();
        $company = Company::first();

        $client_ip = request()->ip();
        $terminal = DB::table('terminal')->where('client_ip', $client_ip)->first();

        $location = Location::first();
        $todaydate = date('Y-m-d');

        $startdatetime = $todaydate . " " . $location->start_work;
        if (date('Y-m-d H:i:s') > $startdatetime) {
            $startdatetime = $startdatetime;
        } else {
            $startdatetime = date('Y-m-d', strtotime(' -1 day')) . " " . $location->start_work;
        }

        $eoddetails = DB::table('eoddetails')->
        whereBetween('created_at',
            [$startdatetime, date('Y-m-d H:i:s')])->first();

        if ($eoddetails != null) {
            $pshift = DB::table('pshift')->
            whereBetween('created_at',
                [$startdatetime, date('Y-m-d H:i:s')])->first();

            if ($pshift === null) {
                $pshift_id = DB::table('pshift')->insertGetId([
                    "eoddetails_id" => $eoddetails->id,
                    "endpshift_presser_user_id" => $user->id,
                    "terminal_id" => $terminal->id,
                    "location_id" => $location->id,
                    "created_at" => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                DB::table('pshiftdetails')->insert([
                    "pshift_id" => $pshift_id,
                    "eoddetails_id" => $eoddetails->id,
                    "startdate" => date('Y-m-d'),
                    "total_amount" => DB::table('receiptdetails')->
                    whereBetween('created_at',
                        [$startdatetime, date('Y-m-d H:i:s')])->sum('total'),
                    "rounding" => DB::table('receiptdetails')->
                    whereBetween('created_at',
                        [$startdatetime, date('Y-m-d H:i:s')])->sum('rounding'),
                    "sales" => DB::table('receiptdetails')->
                    whereBetween('created_at',
                        [$startdatetime, date('Y-m-d H:i:s')])->sum('item_amount'),
                    "sst" => DB::table('receiptdetails')->
                    whereBetween('created_at',
                        [$startdatetime, date('Y-m-d H:i:s')])->sum('sst'),
                    "discount" => DB::table('itemdetails')->
                    whereBetween('created_at',
                        [$startdatetime, date('Y-m-d H:i:s')])->sum('discount'),
                    "cash" => DB::table('receiptdetails')->
                    whereBetween('created_at',
                        [$startdatetime, date('Y-m-d H:i:s')])->sum('cash_received'),
                    "cash_change" => DB::table('receiptdetails')->
                    whereBetween('created_at',
                        [$startdatetime, date('Y-m-d H:i:s')])->sum('change'),
                    "creditcard" => DB::table('receiptdetails')->
                    whereBetween('created_at',
                        [$startdatetime, date('Y-m-d H:i:s')])->sum('creditcard'),

					"wallet" => DB::table('receiptdetails')->
                    whereBetween('created_at',
						[$startdatetime, date('Y-m-d H:i:s')])->sum('wallet'),
                    "created_at" => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            } else {
                $pshift = DB::table('pshift')->
                whereBetween('created_at',
                    [$startdatetime, date('Y-m-d H:i:s')])->first();

                DB::table('pshiftdetails')->
                where('pshift_id', $pshift->id)->update([
                    "total_amount" => DB::table('receiptdetails')->
                    whereBetween('created_at',
                        [$startdatetime, date('Y-m-d H:i:s')])->
                    sum('total'),
                    "rounding" => DB::table('receiptdetails')->
                    whereBetween('created_at',
                        [$startdatetime, date('Y-m-d H:i:s')])->
                    sum('rounding'),
                    "sales" => DB::table('receiptdetails')->
                    whereBetween('created_at',
                        [$startdatetime, date('Y-m-d H:i:s')])->
                    sum('item_amount'),
                    "sst" => DB::table('receiptdetails')->
                    whereBetween('created_at',
                        [$startdatetime, date('Y-m-d H:i:s')])->
                    sum('sst'),
                    "discount" => DB::table('itemdetails')->
                    whereBetween('created_at',
                        [$startdatetime, date('Y-m-d H:i:s')])->
                    sum('discount'),
                    "cash" => DB::table('receiptdetails')->
                    whereBetween('created_at',
                        [$startdatetime, date('Y-m-d H:i:s')])->
                    sum('cash_received'),
                    "cash_change" => DB::table('receiptdetails')->
                    whereBetween('created_at',
                        [$startdatetime, date('Y-m-d H:i:s')])->
                    sum('change'),
                    "creditcard" => DB::table('receiptdetails')->
                    whereBetween('created_at',
                        [$startdatetime, date('Y-m-d H:i:s')])->
                    sum('creditcard'),
					"wallet" => DB::table('receiptdetails')->
                    whereBetween('created_at',
                        [$startdatetime, date('Y-m-d H:i:s')])->
                    sum('wallet'),
                    "created_at" => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        $pshiftdetailsdata = DB::table('pshiftdetails')->
        whereBetween('created_at',
            [$startdatetime, date('Y-m-d H:i:s')])->first();

        return view('local_cabinet.pss', compact(
            'company', 'terminal', 'location', 'user', 'pshiftdetailsdata'));
    }


    public function check_personal_shift()
    {
        $loginout = DB::table('loginout')->where('user_id',1)->latest()->first();

        if (date("Y-m-d", strtotime($loginout->login)) != date("Y-m-d")) {
            DB::table('loginout')->where('id', $loginout->id)->update([
                'logout' => date("Y-m-d 23:59:59", strtotime($loginout->login)),
                'updated_at' => now()
            ]);

            DB::table('loginout')->insert([
                'login' => date("Y-m-d 00:00:00"),
                'location_id' => $loginout->location_id,
                'user_id' => $loginout->user_id,
                'shift_id' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    public function pshift_table(Request $request)
    {
        $this->check_personal_shift();
        $pshift = DB::table('loginout')->
        join('users', 'users.id', 'loginout.user_id')->
        whereDate('loginout.created_at', date('Y-m-d', strtotime($request->date)))->
        select('loginout.*', 'users.systemid')->
        orderBy('loginout.created_at', 'desc')->
        get();

        return view('local_cabinet.personal_shift_table', compact('pshift'));
    }

    public function pshift_detail(Request $request)
    {

        $login_time = $request->login_time;
        $logout_time = $request->logout_time ?? now();
        $user_systemid = $request->user_systemid;

        $client_ip = request()->ip();
        $terminal = DB::table('terminal')->where('client_ip', $client_ip)->first();

        $company = Company::first();
        $location = Location::first();
        $user = DB::table('users')->where('systemid', $user_systemid)->first();

        $data = DB::table('receiptdetails')->
        join('receipt', 'receipt.id', 'receiptdetails.receipt_id')->
        where('receipt.staff_user_id', $user->id)->
        whereNull('receipt.voided_at')->
        whereBetween('receiptdetails.created_at',
            [date('Y-m-d H:i:s', strtotime($login_time)), date('Y-m-d H:i:s', strtotime($logout_time))])->
        select('receiptdetails.*')->
        get();

        $sales = number_format($data->sum('item_amount') / 100, 2);
        $cash = number_format(($data->sum('cash_received') - $data->sum('change')) / 100, 2);
        $creditcard = number_format($data->sum('creditcard') / 100, 2);
        $wallet = number_format($data->sum('wallet') / 100, 2);
        $tax = number_format($data->sum('sst') / 100, 2);
        $round = number_format($data->sum('rounding') / 100, 2);

        $logout_time = $request->logout_time ?? '';
        return view('local_cabinet.pss', compact('terminal', 'company', 'user', 'wallet',
            'location', 'sales', 'cash', 'creditcard', 'tax', 'round', 'login_time', 'logout_time'));
    }

    public function ShiftPopup(Request $request)
    {

        $date = date('Y-m-d', strtotime($request->date));

        Log::debug('Request: ' . $date);
        $pshift = DB::table('pshift')->join('users', 'users.id', '=', 'pshift.endpshift_presser_user_id')
            ->whereBetween('pshift.created_at', [$date . ' 00:00:01', $date . ' 23:59:59'])
            ->select('pshift.*', 'users.fullname', 'users.systemid')
            ->get();

        Log::debug('PSS Data: ' . json_encode($pshift));

        return view('local_cabinet.personal_shift_table', compact('pshift'));
        /*
            return Datatables::of($pshift)
                ->addIndexColumn()
                ->addColumn('date',function($data){
                    return $data->created_at;
                })
                ->addColumn('user', function($data){
                    $user = DB::table('user')->where('id', $data->endpshift_presser_user_id)->select('systemid');

                    return '<a onclick="pShiftReceiptModal('.$user->systemid.','.$data->created_at.')">'.$user->systemid.'</a>';
                })
                ->escapeColumns([])
                ->make(true);
                */
    }


    public function PersonalShiftDetails(Request $request)
    {
        $eod_date = $request->eod_date;

        Log::debug('Pshift: ' . $eod_date);
        if ($request->eod_date) {
            $date_eod = date_create($request->eod_date);
            $date_eod = date_format($date_eod, 'Y-m-d');
        } else {
            $date_eod = date('Y-m-d');
        }

        $user = Auth::user();
        $location = Location::first();
        $todaydate = date('Y-m-d');

        $client_ip = request()->ip();
        $terminal = DB::table('terminal')->where('client_ip', $client_ip)->first();

        $eoddetailsdata = DB::table('eoddetails')->
        whereDate('startdate', '=', $date_eod)->first();

        $receipts = Receipt::with('receiptdetails')->whereDate('receipt.created_at', '=', $date_eod)
            ->where('receipt.status', 'voided')
            ->get();

        $reverseAmount = 0;
        $reverseCash = 0;
        $reverseCard = 0;
        $reverseTax = 0;

        foreach ($receipts as $receipt) {
            if ($receipt->payment_type == 'creditcard') {
                $reverseCard += $receipt->receiptdetails->creditcard;
            } else {
                $reverseCash += $receipt->receiptdetails->cash_received - $receipt->receiptdetails->change;
            }
            $reverseTax += $receipt->receiptdetails->sst;
        }
        $reverseAmount += ($reverseCard + $reverseCash) - $reverseTax;

        $refund_data = DB::table('receiptrefund')->
        join('receipt', 'receipt.id', 'receiptrefund.receipt_id')->
        whereDate('receipt.created_at', '=', $date_eod)->get();

        $refundTax = 0;
        $refundAmount = 0;
        $refundCash = 0;
        $refundCard = 0;

        foreach ($refund_data as $receipt) {
            if ($receipt->payment_type == 'creditcard') {
                $refundCard += ($receipt->refund_amount * 100);
            } else {
                $refundCash += ($receipt->refund_amount * 100);
            }

            $refundAmount += $refundCash + $refundCard;

            $refundTax = $refundAmount / 100 * $terminal->tax_percent;
            $refundAmount -= $refundTax;
        }


        $reverseAmount += $refundAmount;
        $reverseCash += $refundCash;
        $reverseCard += $refundCard;
        $reverseTax += $refundTax;


        $sst_tax = $eoddetailsdata->sst - $reverseTax;
        $round = ($eoddetailsdata->sales + $sst_tax - $reverseAmount) / 100;
        $round = (float)number_format($this->round_amount($round) / 100, 2);

        \Log::info([
            'refund amount' => $refundAmount,
            'refund Cash' => $refundCash,
            'refundCard' => $refundCard,
            'refundTax' => $refundTax,
            'sst_tax' => $sst_tax
        ]);


        $user = Auth::user();
        $company = Company::first();
        $location = Location::first();
        return view('local_cabinet.eod_summarylist', compact('round',
            'company', 'terminal', 'location', 'user', 'eoddetailsdata', 'reverseAmount', 'reverseTax', 'reverseCash', 'reverseCard', 'eod_date'));
    }


    public function store_last_filled(Request $request)
    {
        Log::info('store_last_filled: auth_id=' . $request->auth_id .
            ', filled=' . $request->filled);

        try {
			$is_exist =  DB::table('receiptfilled')->
				where('auth_systemid' , $request->auth_id)->first();

			if(!empty($is_exist)) {
				Log::debug('store_last_filled: is_exist!  auth_systemid=' . $request->auth_id .
					', filled=' . $request->filled);
				return;
			}


			// Squidster: This is where we insert filled
            $ret = DB::table('receiptfilled')->insert([
                'auth_systemid' => $request->auth_id,
                'filled' => $request->filled,
                'created_at' => now(),
                'updated_at' => now()
            ]);

			Log::debug('store_last_filled: SUCCESSFUL INSERT ret='.
				$ret. ':  auth_systemid=' . $request->auth_id.
				', filled=' . $request->filled);

        } catch (Exception $e) {
            Log::error('**** store_last_filled() ****');
            Log::error([
                'Message' => $e->getMessage(),
                'File' => $e->getFile(),
                'Line' => $e->getLine(),
            ]);
        }
    }

	function generate_auth_id() {
		try {

			$systemid = new SystemID('authorize');
			
		
			DB::table('authreceipt')->insert([
				"auth_systemid" => $systemid,
				"created_at"	=> now(),
				"updated_at"	=> now()
			]);

			return $systemid;
        } catch (\Exception $e) {
            return ["message" => $e->getMessage(), "error" => true];
        }
	}

    function optList()
    {
        try {
            $opt = [];
            return view("local_cabinet.opt_table", compact("opt"));

        } catch (\Exception $e) {
            return ["message" => $e->getMessage(), "error" => false];
        }
    }


    function optListData()
    {

        try {

            return Datatables::of([])
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);


        } catch (\Exception $e) {
            return ["message" => $e->getMessage(), "error" => false];
        }
    }

    function evList()
    {

        try {
            $ev = [];
            return view("local_cabinet.ev_table", compact("ev"));

        } catch (\Exception $e) {
            return ["message" => $e->getMessage(), "error" => false];
        }
    }


    function evListData()
    {

        try {
            $data = CarPark::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a  href="javascript:void(0)" onclick="actionClick(' . $row->id . ')" data-row="' . $row->id . '" class="delete"> <img width="25px" src="images/bluecrab_50x50.png" alt=""> </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);


        } catch (\Exception $e) {
            return ["message" => $e->getMessage(), "error" => false];
        }
    }

    function evReceiptDetail()
    {

        try {
            $company = Company::first();
            $receipt = Receipt::first();
            $receiptdetails = ReceiptDetails::first();
            $refund = ReceiptRefund::first();
            return view("local_cabinet.ev_receipt", compact("company","receipt","receiptdetails","refund"));

        } catch (\Exception $e) {
            return ["message" => $e->getMessage(), "error" => false];
        }
    }


     function loyaltyPoints(Request $request){
        try {
           
        $receipt = Receipt::find($request->id);
        $receiptproduct = DB::table('receiptproduct')->
        leftjoin('product', 'product.id', 'receiptproduct.product_id')->
        where('receipt_id', $receipt->id)->get();
        $loyatypoints = array();

        $i=0;
        foreach ($receiptproduct as $pro) {
            $loyatypoints[$i]['systemid']= $pro->systemid;
            $loyatypoints[$i]['name']= $pro->name;
            $loyatypoints[$i]['quantity']= $pro->quantity;
            $loyatypoints[$i]['thumb']= $pro->thumbnail_1;
            $loyatypoints[$i]['price']= $pro->price;
            $sub_prod_table='';
            switch ($pro->ptype) {
                case 'oilgas':
                    $sub_prod_table='prd_ogfuel';
                    break;
                case 'inventory':
                    $sub_prod_table='prd_openitem';
                     break;
                default:
                 $loyatypoints[$i]['loyalty']= 0;
            }
            if($sub_prod_table!=''){
                $prd_ = DB::table($sub_prod_table)->
                where('product_id', $pro->product_id)->get()->first();
                $loyatypoints[$i]['loyalty']= $prd_->loyalty;
            }
        }


		return view('local_cabinet.loyalty_point',
			compact('loyatypoints'));

        } catch (Exception $e) {
            Log::error([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            abort(404);
        }
    }
}



