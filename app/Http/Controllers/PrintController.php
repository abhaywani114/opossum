<?php

namespace App\Http\Controllers;


use \App\Classes\SystemID;
use App\Models\FuelReceipt;
use App\Models\User;
use App\Models\Company;
use App\Models\Receipt;
use App\Models\ReceiptRefund;
use App\Models\Location;
use App\Models\Terminal;
use Milon\Barcode\DNS2D;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;



class PrintController extends Controller
{

	public function print_receipt(Request $request) {
		$milon = new DNS2D;
		$receipt = Receipt::find($request->receipt_id);
		$qrcode = $milon->getBarcodePNG($receipt->systemid, "QRCODE");
        $receipt = Receipt::find($receipt->id);
        $user = User::where('id', $receipt->staff_user_id)->first();
        $company = Company::where('id', $receipt->company_id)->first();
        $terminal = Terminal::where('id', $receipt->terminal_id)->first();
        $location = Location::first();

        $receiptproduct = DB::table('receiptproduct')->
        where('receipt_id', $receipt->id)->get();

        $reference = DB::table('receiptrefund')->
                join('users', 'receiptrefund.staff_user_id', '=', 'users.id')
                ->where('receipt_id', $receipt->id)
                ->select('receiptrefund.*', 'users.fullname as name', 'users.systemid as systemid')
                ->first();
        $refund ='';
        if($reference){
            $refund = $reference;
            return  view('printing.localfuel_refund_receipt', compact(
                'company',
                'terminal',
                'location',
                'user',
                'receipt',
                'receiptproduct',
                'qrcode',
                'refund'
            ));
        }

        if($receipt->status == "voided"){

            $voiduser = User::find($receipt->void_user_id);
           return  view('printing.void_fuel_receipt_template_escpos', compact(
                'company',
                'terminal',
                'location',
                'user',
                'receipt',
                'receiptproduct',
                'qrcode',
                'voiduser'
            ));
        }
		return view('printing.fuel_receipt_template_escpos', compact(
            'company',
            'terminal',
            'location',
            'user',
            'receipt',
            'receiptproduct',
            'qrcode'
        ));
	}

	public function print_fuel_receipt(Request $request) {
		$milon = new DNS2D;
		$receipt = FuelReceipt::find($request->receipt_id);
		$qrcode = $milon->getBarcodePNG($receipt->systemid, "QRCODE");
        $user = User::where('id', $receipt->staff_user_id)->first();
        $company = Company::where('id', $receipt->company_id)->first();
        $terminal = Terminal::where('id', $receipt->terminal_id)->first();
        $location = Location::first();

        $receiptproduct = DB::table('fuel_receiptproduct')->
        where('receipt_id', $receipt->id)->get();

        $reference = DB::table('receiptrefund')->
                join('users', 'receiptrefund.staff_user_id', '=', 'users.id')
                ->where('receipt_id', $receipt->id)
                ->select('receiptrefund.*', 'users.fullname as name', 'users.systemid as systemid')
                ->first();
        $refund ='';
        if($reference){
            $refund = $reference;
            return  view('printing.refund_fuel_receipt_template_escpos', compact(
                'company',
                'terminal',
                'location',
                'user',
                'receipt',
                'receiptproduct',
                'qrcode',
                'refund'
            ));
        }

        if($receipt->status == "voided"){

            $voiduser = User::find($receipt->void_user_id);
           return  view('printing.void_fuel_receipt_template_escpos', compact(
                'company',
                'terminal',
                'location',
                'user',
                'receipt',
                'receiptproduct',
                'qrcode',
                'voiduser'
            ));
        }
		return view('printing.fuel_receipt_template_escpos', compact(
            'company',
            'terminal',
            'location',
            'user',
            'receipt',
            'receiptproduct',
            'qrcode'
        ));
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
    public function eod_print(Request $request) {

        Log::debug('EOD Print Start: '.json_encode($request->eod_date));
        //dd($request->eod_date);
        $eod_date=$request->eod_date;
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

        $receipts = Receipt::with('receiptdetails')->
			whereDate('receipt.created_at', '=', $date_eod)->
			where('receipt.status', 'voided')->
            get();


        $reverseAmount = 0;
        $reverseCash = 0;
        $reverseCard = 0;
		$reverseWallet = 0;
        $reverseTax = 0;

        foreach ($receipts as $receipt){
            if ($receipt->payment_type == 'creditcard'){
                $reverseCard += $receipt->receiptdetails->creditcard;
			} elseif ($receipt->payment_type == 'wallet'){
				$reverseWallet += $receipt->receiptdetails->wallet; 
			} else{
                $reverseCash += $receipt->receiptdetails->cash_received - $receipt->receiptdetails->change;
            }
            $reverseTax += $receipt->receiptdetails->sst;
        }
        $reverseAmount += ($reverseCard+$reverseCash+$reverseWallet)-$reverseTax;

		/*
		$refund_data = DB::table('receiptrefund')->
			join('receipt','receipt.id','receiptrefund.receipt_id')->
			whereDate('receipt.created_at', '=', $date_eod)->get();*/
		
		$refundAmount = DB::table('receiptrefund')->
			join('receipt','receipt.id','receiptrefund.receipt_id')->
			whereDate('receipt.created_at', '=', $date_eod)->get()->sum('refund_amount');

	/*/
		$refundTax = 0;
		$refundAmount = 0;
		$refundCash = 0;
        $refundCard = 0;
        $totalRefund = 0;

		foreach ($refund_data as $receipt){
			if ($receipt->payment_type == 'creditcard'){
				$refundCard += $receipt->refund_amount * 100;
			} else {
				$refundCash += $receipt->refund_amount * 100;
			}

			$refundAmount += $refundCash + $refundCard;

			$refundTax = $refundAmount / 100 * $terminal->tax_percent;
			$refundAmount -= $refundTax;
		}


		$reverseAmount += $refundAmount;
        $reverseCash += $refundCash;
        $reverseCard += $refundCard;
        $reverseTax += $refundTax;
/*/
		$refund_p_amount = $refundAmount;
		$tax_percent = ($terminal->tax_percent ?? 6);
		$refundAmount = ($refundAmount) / (1 + ($tax_percent / 100));
		$refund_sst = $refund_p_amount - $refundAmount;
		$refund_round = $refundAmount + $refund_sst;
		$refund_round = (float) number_format($this->round_amount($refund_round) /100, 2);

			
        $sst_tax = ( $eoddetailsdata->sst ?? 0) - $reverseTax;
		$round = (($eoddetailsdata->sales ?? 0)  + $sst_tax - $reverseAmount) / 100;
		$round = (float) number_format($this->round_amount($round) /100, 2);


        $user = Auth::user();
        $company = Company::first();
        $location = Location::first();
        /*
        return view('printing.eod_print_template_escpos', compact(
            'company', 'terminal', 'location', 'user', 'eoddetailsdata', 'reverseAmount','reverseTax','reverseCash','reverseCard'));
            */

        if($refundAmount > 0){
            return view('printing.eod_refund_template_escpos', compact(
				'round','refund_sst','refund_round','refundAmount',
                'company', 'terminal', 'location', 'user','reverseWallet',
				'eoddetailsdata', 'reverseAmount','reverseTax',
				'reverseCash','reverseCard', 'eod_date'));
        } else {
            return view('printing.eod_template_escpos', compact(
				'round', 'company', 'terminal', 'location', 'user',
				'eoddetailsdata', 'reverseAmount','reverseTax','reverseWallet',
				'reverseCash','reverseCard', 'eod_date'));
        }
    }


    public function PersonalShiftPrint(Request $request)
    {
        Log::debug('Request: '.json_encode($request->all()));
		$login_time  	= $request->login_time;
		$logout_time 	= $request->logout_time ?? now();
		$user_systemid 	= $request->user_systemid;

		$client_ip = request()->ip();
        $terminal = DB::table('terminal')->where('client_ip', $client_ip)->first();

        $company = Company::first();
        $location = Location::first();
		$user = DB::table('users')->where('systemid', $user_systemid)->first();

		$data =  DB::table('receiptdetails')->
			join('receipt','receipt.id','receiptdetails.receipt_id')->
			where('receipt.staff_user_id', $user->id)->
			whereNull('receipt.voided_at')->
			whereBetween('receiptdetails.created_at',
				[date('Y-m-d H:i:s', strtotime($login_time)), date('Y-m-d H:i:s', strtotime($logout_time))])->
			select('receiptdetails.*')->
			get();

		$sales			= number_format( $data->sum('item_amount')/ 100, 2);
		$cash			= number_format( ($data->sum('cash_received') - $data->sum('change')) / 100, 2);
		$creditcard		= number_format( $data->sum('creditcard') / 100, 2);
		$wallet			= number_format( $data->sum('wallet') / 100, 2);
		$tax 			= number_format( $data->sum('sst') / 100 , 2 );
		$round			= number_format( $data->sum('rounding') / 100, 2);

		$logout_time 	= $request->logout_time ?? '';

		return view('printing.pshift_print', compact('terminal', 'company', 'user','wallet',
			'location', 'sales', 'cash', 'creditcard', 'tax', 'round', 'login_time','logout_time'));	
    }
}



