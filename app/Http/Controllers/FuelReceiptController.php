<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarPark;
use App\Models\FuelReceiptdetails;
use App\Models\ReceiptRefund;
use Log;
use App\Models\Company;
use App\Models\Location;
use App\Models\FuelReceipt;
use App\Models\Terminal;
use Illuminate\Support\Facades\Auth;
use \App\Classes\SystemID;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class FuelReceiptController extends Controller
{
    //

    function fuelReceipList(Request $request)
    {
        $date = $request->date;
       

        return view('fuel_receipt.fuel_receiptlist', compact('date'));
    }


    function CreateFuelList(Request $request)
    {
        try {
            $client_ip = request()->ip();
            $terminal = DB::table('terminal')->
            where('client_ip', $client_ip)->first();

            $user = Auth::user();
            $company = Company::first();
            $location = Location::first();
            $systemid = Systemid::receipt_system_id($terminal->id);
            $pump_hardware = DB::table('local_pump')->
            where("pump_no", $request->pump_no)->first();
            $receipt = new FuelReceipt();
            $receipt->systemid = $systemid;

            if ($request->payment_type == "card") {
                $receipt->payment_type = "creditcard";
                $receipt->creditcard_no = $request->creditcard_no ?? 0;
                //$receipt->cash_received = ($request->cash_received ?? 0) * 100;
                $receipt->cash_received = 0;

            } elseif ($request->payment_type == 'wallet') {
                $receipt->payment_type = "wallet";
                //$receipt->cash_received = ($request->cash_received ?? 0) * 100;
                $receipt->cash_received = 0;

            }elseif ($request->payment_type == 'creditac') {
                $receipt->payment_type = "creditac";
                //$receipt->cash_received = ($request->cash_received ?? 0) * 100;
                $receipt->cash_received = 0;

            } else {
                $receipt->payment_type = $request->payment_type;
                $receipt->cash_received = ($request->cash_received ?? 0) * 100;
                $receipt->cash_change = ($request->change_amount ?? 0) * 100;
            }


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
                    "receipt_id" => $receipt->id,
                    "updated_at" => now()
                ]);
            } catch (\Exception $e) {
                \Log::info("authreceipt failed : " . $e->getMessage());
            }
            $receiptproductsdiscount = 0;

            $receiptproduct_id = DB::table('fuel_receiptproduct')->insertGetId([
                "receipt_id" => $receipt->id,
                "product_id" => $request->product_id,
                "name" => $request->product,
                "quantity" => $request->qty,
                "price" => $request->price * 100,
                "discount_pct" => 0,
                "discount" => 0,
                "created_at" => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $amount = (float)number_format($request->item_amount);
            $price = (float)number_format($request->price);
            $sst = (float)number_format($request->sst);
            $total_amount = (float)number_format($request->dose);
            $rounding = (float)number_format($request->cal_rounding);

            DB::table('fuel_itemdetails')->insert([
                "receiptproduct_id" => $receiptproduct_id,
                "amount" => $request->item_amount * 100,
                "rounding" => $request->cal_rounding,
                "price" => $request->price * 100,
                "sst" => $request->sst * 100,
                "discount" => 0,
                "created_at" => $receipt->created_at,
                'updated_at' => $receipt->created_at,
            ]);

            $cash_received = 0;
            $cash_change = 0;
            $creditcard = 0;
            $creditac=0;
            if ($receipt->payment_type == "cash") {
                $cash_received = $request->cash_received;
            } elseif ($receipt->payment_type == "wallet") {
                $wallet = $request->dose;
            } elseif ($receipt->payment_type == "creditac") {
                $creditac = $request->dose;
            } else {
                $creditcard = $request->dose;
            }

            DB::table('fuel_receiptdetails')->insert([
                "receipt_id" => $receipt->id,
                "total" => $request->dose * 100,
                "rounding" => $request->cal_rounding * 100,
                "item_amount" => $request->item_amount * 100,
                "sst" => $request->sst * 100,
                "discount" => $receiptproductsdiscount * 100,
                "cash_received" => $cash_received * 100,
                "change" => $request->change_amount * 100,
                "creditcard" => $creditcard * 100,
                "wallet" => ($wallet ?? 0) * 100,
                "creditac" => ($creditac ?? 0) * 100,
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
                    "total_amount" => DB::table('fuel_receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('total'),
                    "rounding" => DB::table('fuel_receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('rounding'),
                    "sales" => DB::table('fuel_receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('item_amount'),
                    "sst" => DB::table('fuel_receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('sst'),
                    "discount" => DB::table('fuel_itemdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('discount'),
                    "cash" => DB::table('fuel_receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('cash_received'),
                    "cash_change" => DB::table('fuel_receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('change'),
                    "creditcard" => DB::table('fuel_receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('creditcard'),
                    // "wallet" => DB::table('fuel_receiptdetails')->
                    // whereDate('created_at', '=', date('Y-m-d'))->sum('wallet'),
                    "created_at" => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            } else {
                DB::table('eoddetails')->
                where('eod_id', $brancheoddata->id)->update([
                    "total_amount" => DB::table('fuel_receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('total'),
                    "rounding" => DB::table('fuel_receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('rounding'),
                    "sales" => DB::table('fuel_receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('item_amount'),
                    "sst" => DB::table('fuel_receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('sst'),
                    "discount" => DB::table('fuel_itemdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('discount'),
                    "cash" => DB::table('fuel_receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('cash_received'),
                    "cash_change" => DB::table('fuel_receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('change'),
                    // "wallet" => DB::table('fuel_receiptdetails')->
                    // whereDate('created_at', '=', date('Y-m-d'))->sum('wallet'),
                    "creditcard" => DB::table('fuel_receiptdetails')->
                    whereDate('created_at', '=', date('Y-m-d'))->sum('creditcard'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
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


   


    public function fuelReceipt(Request $request)
    {
        try {
            $user = Auth::user();
            $location = Location::first();
            $company = Company::first();
            $currencyarr = DB::table('currency')->where('id',$company->currency_id)->orderBy('code')->get()->first();
            $currency = $currencyarr->code ?? 'MYR';
            $receipt = FuelReceipt::with("user")->where('id', $request->id)->first();
            $receiptdetails = FuelReceiptdetails::where('receipt_id', $request->id)->first();
            $receiptproduct = DB::table('fuel_receiptproduct')->where('receipt_id', $request->id)->get();
            $refund = ReceiptRefund::where("receipt_id",$request->id)
                        ->join('users' , 'users.id' , 'receiptrefund.staff_user_id')
                        ->first();
            $client_ip = request()->ip();
            $terminal = DB::table('terminal')->where('client_ip', $client_ip)->first();
            // \Illuminate\Support\Facades\Log::info(json_encode($receipt));
            return view("fuel_receipt.fuel_receipt", compact("currency", "location", "user", "receiptproduct", "terminal", "company", "receipt", "receiptdetails", "refund"));

        } catch (\Exception $e) {
            return ["message" => $e->getMessage(), "error" => false];
        }
    }


    public function fuelRefund(Request $request)
    {
        
        $receipt = DB::table('fuel_receipt')->
        selectRaw('fuel_receipt.* , fuel_receiptdetails.* , fuel_receiptproduct.* ,receiptfilled.* , fuel_receipt.id as receipt_id, fuel_receiptproduct.quantity as quantity  ')->
        join('fuel_receiptdetails', 'fuel_receiptdetails.receipt_id', 'fuel_receipt.id')->
        join('fuel_receiptproduct', 'fuel_receiptproduct.receipt_id', 'fuel_receipt.id')->
        leftjoin('authreceipt', 'authreceipt.receipt_id', 'fuel_receipt.id')->
        leftjoin('receiptfilled', 'receiptfilled.auth_systemid', 'authreceipt.auth_systemid')->
        whereNull('fuel_receipt.deleted_at')->
        where('fuel_receipt.id' , $request->id)->
        first();
        $refund  = ($receipt->total/100) - $receipt->filled;    
        DB::table('receiptrefund')->
        insert([
            "receipt_id" => $request->id,
            "staff_user_id"=> $receipt->staff_user_id , 
            "refund_amount"=> $refund,
            "qty"=>$receipt->quantity , 
            "created_at" => now(),
            "updated_at" => now()
        ]);
        DB::table('fuel_receipt')->where([
            "id" => $receipt->receipt_id
        ])->update([
            "status" => 'refunded',
            "updated_at" => now()
        ]);
        \Illuminate\Support\Facades\Log::info('fuelRefund refund_amount:'.$refund);
        return ["message" => "successfully refund", "error" => false];
    }

    public function dataTable(Request $request)
    {
        try {
            $data = DB::table('fuel_receipt')->
            selectRaw('fuel_receipt.* , fuel_receiptdetails.* , fuel_receiptproduct.* ,receiptfilled.* , fuel_receipt.id as receipt_id , fuel_receipt.created_at as created_at ')->
            join('fuel_receiptdetails', 'fuel_receiptdetails.receipt_id', 'fuel_receipt.id')->
            join('fuel_receiptproduct', 'fuel_receiptproduct.receipt_id', 'fuel_receipt.id')->
            leftjoin('authreceipt', 'authreceipt.receipt_id', 'fuel_receipt.id')->
            leftjoin('receiptfilled', 'receiptfilled.auth_systemid', 'authreceipt.auth_systemid')->
            whereNull('fuel_receipt.deleted_at')->
            whereDate('fuel_receipt.created_at', date('Y-m-d', strtotime($request->date)))->
            orderBy('fuel_receipt.id', 'DESC')->get();

            
            return Datatables::of($data)->
            addIndexColumn()->
            addColumn('date', function ($data) {
                $created_at = Carbon::parse($data->created_at)->format('dMy H:i:s');
                return <<<EOD
					$created_at
EOD;
            })->
            addColumn('isrefunded', function ($data) {
                $systemid  = ($data->status=="refunded")?1:0;
                return <<<EOD
					$systemid
EOD;
            })->
            addColumn('systemid', function ($data) {
                $systemid = !empty($data->systemid) ? '<a href="javascript:void(0)"  style="text-decoration:none;" onclick="getFuelReceiptlist(' . $data->receipt_id . ')" > ' . $data->systemid . '</a>' : 'Receipt ID';
                return <<<EOD
					$systemid
EOD;
            })->
            addColumn('total', function ($data) {
                $total = !empty($data->total) ? number_format($data->total / 100, 2) : '0.00';
                return <<<EOD
					$total
EOD;
            })->
            addColumn('fuel', function ($data) {
                $total = !empty($data->total) ? number_format($data->total / 100, 2) : '0.00';
                return <<<EOD
					$total
EOD;
            })->

            addColumn('filled', function ($data) {
                $filled = !empty($data->filled) ? number_format($data->filled, 2) : '0.00';
                return <<<EOD
					$filled
EOD;
            })->
            addColumn('refund', function ($data) {
                $refund = "0.00";
 
                $refund = number_format(($data->total/100 - $data->filled),2);
                    \Illuminate\Support\Facades\Log::info($refund);
                return <<<EOD
					$refund
EOD;
            })->
            addColumn('action', function ($data) {
                $action = '';
                return <<<EOD
					$action
EOD;
            })->
            addColumn('action', function ($row) {
                $refund  =$row->total/100 - $row->filled ;
                if( $row->status!="refunded" &&  $refund > 0 && $row->status!="voided"){
                    $btn = '<a  href="javascript:void(0)"  onclick="refundMe(' . $row->receipt_id . ')" data-row="' . $row->id . '" class="delete"> <img width="25px" src="'.asset("images/bluecrab_50x50.png").'" alt=""> </a>';
                return $btn;
                }else{
                    $btn = '<a  href="javascript:void(0)"   disabled="disabled" style=" filter: grayscale(100) brightness(1.5); pointer-events: none;cursor: default;" data-row="' . $row->id . '" class="delete"> <img width="25px" src="'.asset("images/bluecrab_50x50.png").'"" alt=""> </a>';
                return $btn;
                }
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
                editColumn('systemid', function ($data) {
                    $systemid = !empty($data->systemid) ? '<a href="#" style="text-decoration:none" onclick="getFuelReceiptlist(' . $data->receipt_id . ')" > ' . $data->systemid . '</a>' : 'Receipt ID';
                    return <<<EOD
                        $systemid
    EOD;
                })->
            rawColumns(['action'])->
            escapeColumns([])->
            make(true);

        } catch (Exception $e) {
            Log::info([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line No" => $e->getLine()
            ]);
            abort(404);
        }
    }


    public function voidedReceipt(Request $request)
    {
        try {


            $fuel_receipt = FuelReceipt::find($request->receipt_id);
            $fuel_receipt->status = "voided";
            $fuel_receipt->voided_at = now();
            $fuel_receipt->voided_at = now();
            $fuel_receipt->void_user_id = Auth::user()->id;
            $fuel_receipt->save();
            return ["data"=>$fuel_receipt,'error'=>false];
        } catch (Exception $e) {
            Log::info([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line No" => $e->getLine(),
                'error'=>false,
            ]);
            return ["data"=>[],'error'=>false];
        }

    }





}
