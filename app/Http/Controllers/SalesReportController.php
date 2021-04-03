<?php

namespace App\Http\Controllers;

use App\Classes\FuelUsageExport;
use App\Exports\InvoicesExport;
use App\Models\CommReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use PDF;
use App\Models\Company;

class SalesReportController extends Controller
{
    //
    public function generate(Request $request)
    {
         return view("sales_report.sales_report");
         //return view("landing.sample_datepicker");
    }

    public function printPDF(Request $request){
        Log::debug('Request: '.json_encode($request->all()));

        $location = DB::table('location')->first();

        //Change date Format
        $requestValue = $request->all();

        $start = date('Y-m-d', strtotime($request->start_date));
        $stop = date('Y-m-d', strtotime($request->end_date));

        Log::debug('Start Date: '.$start);
        Log::debug('Stop Date: '.$stop);
        /*
        $product_details= DB::table('receiptdetails')
			->join('receipt', 'receipt.id', '=', 'receiptdetails.receipt_id')
			->join('receiptproduct','receiptproduct.receipt_id', '=', 'receipt.id')
			 ->whereBetween('receiptdetails.created_at', [$start.' 00:00:00', $stop.' 23:59:59'])
			 ->get();
        */
        $products = DB::table('product')->
						whereNotIn('ptype',['oilgas'])->
                        get();
        $product_details= [];
        foreach($products as $product){
            $item_amount = 	DB::table('cstore_receiptdetails')
                ->join('cstore_receiptproduct', 'cstore_receiptproduct.receipt_id', '=', 'cstore_receiptdetails.receipt_id')
                ->join('cstore_receipt', 'cstore_receiptproduct.receipt_id','=','cstore_receipt.id')
                ->where('cstore_receiptproduct.product_id', '=', $product->id)
                ->where('cstore_receipt.status', '!=', 'voided')
                ->whereBetween('cstore_receiptdetails.created_at', [$start.' 00:00:00', $stop.' 23:59:59'])
                ->sum('item_amount');

            $quantity = 	DB::table('cstore_receiptdetails')
                ->join('cstore_receiptproduct', 'cstore_receiptproduct.receipt_id', '=', 'cstore_receiptdetails.receipt_id')
                ->join('cstore_receipt', 'cstore_receiptproduct.receipt_id','=','cstore_receipt.id')
                ->where('cstore_receipt.status', '!=', 'voided')
                ->where('cstore_receiptproduct.product_id', '=', $product->id)
                ->whereBetween('cstore_receiptdetails.created_at', [$start.' 00:00:00', $stop.' 23:59:59'])
                ->sum('quantity');


			//->sum('receiptrefund.refund_amount');
			
            if($item_amount > 0) {	
				$product->item_amount = $item_amount;// - ($refund * 100);
                $product->quantity = $quantity;
                Log::debug('Product: '.json_encode($product));
                $product_details[] = $product;
            }
        }

		
		$refund = DB::table('cstore_receiptdetails')
			->join('cstore_receiptproduct', 'cstore_receiptproduct.receipt_id', '=', 'cstore_receiptdetails.receipt_id')
			->join('cstore_receipt', 'cstore_receiptproduct.receipt_id','=','cstore_receipt.id')
			->join('cstore_receiptrefund', 'cstore_receiptrefund.cstore_receipt_id', '=', 'cstore_receipt.id')
			->join('product','product.id','cstore_receiptproduct.product_id')
			->where('cstore_receipt.status', '!=', 'voided')	
			->whereBetween('cstore_receiptdetails.created_at', [$start.' 00:00:00', $stop.' 23:59:59'])
			->select('product.name','product.systemid','cstore_receiptproduct.quantity','cstore_receiptrefund.refund_amount')	
			->get();

		$receipt_refund = DB::table('cstore_receipt')
			->join('cstore_receiptrefund', 'cstore_receiptrefund.cstore_receipt_id', '=', 'cstore_receipt.id')
			->where('cstore_receipt.status', '!=', 'voided')
			->whereBetween('cstore_receipt.created_at', [$start.' 00:00:00', $stop.' 23:59:59'])
			->select('cstore_receipt.systemid','cstore_receiptrefund.refund_amount')	
			->get();

        Log::debug('Sales: '.json_encode($product_details));
	   
		$total_refund = DB::table('cstore_receiptdetails')
			->join('cstore_receiptproduct', 'cstore_receiptproduct.receipt_id', '=', 'cstore_receiptdetails.receipt_id')
			->join('cstore_receipt', 'cstore_receiptproduct.receipt_id','=','cstore_receipt.id')
			->join('cstore_receiptrefund', 'cstore_receiptrefund.cstore_receipt_id', '=', 'cstore_receipt.id')
			->where('cstore_receipt.status', '!=', 'voided')
			//->where('receiptproduct.product_id', '=', $product->id)
			->whereBetween('cstore_receiptdetails.created_at', [$start.' 00:00:00', $stop.' 23:59:59'])
			->sum('cstore_receiptrefund.refund_amount');

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
			->loadView('sales_report.sales_report_pdf', compact('product_details', 'requestValue',
			  'total_refund' , 'location', 'refund', 'receipt_refund'));

        // download PDF file with download method
        // $pdf = PDF::loadHTML('<p>Hello World!!</p>');

        // return $pdf->stream();
        $pdf->getDomPDF()->setBasePath(public_path().'/');
        $pdf->getDomPDF()->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'allow_self_signed'=> true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
            ])
        );
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('SalesReport.pdf');
    }


    public function fuelPrintPDF(Request $request){
        Log::debug('Request: '.json_encode($request->all()));
        $company = Company::first();
        $currencyarr = DB::table('currency')->where('id',$company->currency_id)->orderBy('code')->get()->first();
        $currency = $currencyarr->code ?? 'MYR';
        $location = DB::table('location')->first();

        //Change date Format
        $requestValue = $request->all();

        $start = date('Y-m-d', strtotime($request->fuel_start_date));
        $stop = date('Y-m-d', strtotime($request->fuel_end_date));

        Log::debug('Start Date: '.$start);
        Log::debug('Stop Date: '.$stop);
        /*
        $product_details= DB::table('receiptdetails')
			->join('receipt', 'receipt.id', '=', 'receiptdetails.receipt_id')
			->join('receiptproduct','receiptproduct.receipt_id', '=', 'receipt.id')
			 ->whereBetween('receiptdetails.created_at', [$start.' 00:00:00', $stop.' 23:59:59'])
			 ->get();
        */
        $products = DB::table('product')
                        ->get();
        $product_details= [];
        foreach($products as $product){
            $item_amount = DB::table('fuel_receiptdetails')
                ->join('fuel_receiptproduct', 'fuel_receiptproduct.receipt_id', '=', 'fuel_receiptdetails.receipt_id')
                ->join('fuel_receipt', 'fuel_receiptproduct.receipt_id','=','fuel_receipt.id')
                ->where('fuel_receiptproduct.product_id', '=', $product->id)
                ->where('fuel_receipt.status', '!=', 'voided')
                ->whereBetween('fuel_receiptdetails.created_at', [$start.' 00:00:00', $stop.' 23:59:59'])
                ->sum('item_amount');


            $quantity = 	DB::table('fuel_receiptdetails')
                ->join('fuel_receiptproduct', 'fuel_receiptproduct.receipt_id', '=', 'fuel_receiptdetails.receipt_id')
                ->join('fuel_receipt', 'fuel_receiptproduct.receipt_id','=','fuel_receipt.id')
                ->where('fuel_receiptproduct.product_id', '=', $product->id)
                ->where('fuel_receipt.status', '!=', 'voided')
                ->whereBetween('fuel_receiptdetails.created_at', [$start.' 00:00:00', $stop.' 23:59:59'])
                ->sum('fuel_receiptproduct.quantity');

			//->sum('receiptrefund.refund_amount');

            if($item_amount > 0) {
				$product->item_amount = $item_amount;// - ($refund * 100);
                $product->quantity = $quantity;
                Log::debug('Product: '.json_encode($product));
                $product_details[] = $product;
            }
        }


        $refund = DB::table('receiptrefund')
            ->join('fuel_receipt', 'fuel_receipt.id','=','receiptrefund.receipt_id')
            ->join('fuel_receiptproduct', 'fuel_receiptproduct.receipt_id', '=', 'receiptrefund.receipt_id')
            ->join('product','product.id','fuel_receiptproduct.product_id')
            ->where('fuel_receipt.status', '!=', 'voided')
			->where('product.ptype', 'oilgas')
            ->whereBetween('receiptrefund.created_at', [$start.' 00:00:00', $stop.' 23:59:59'])
            ->select('product.name','product.systemid','receiptrefund.qty','receiptrefund.refund_amount','fuel_receiptproduct.price as price' )
			->get();

		// $refund = DB::table('fuel_receiptdetails')
		// 	->join('fuel_receiptproduct', 'fuel_receiptproduct.receipt_id', '=', 'fuel_receiptdetails.receipt_id')
		// 	->join('fuel_receipt', 'fuel_receiptproduct.receipt_id','=','fuel_receipt.id')
		// 	->join('receiptrefund', 'receiptrefund.receipt_id', '=', 'fuel_receipt.id')
		// 	->join('product','product.id','fuel_receiptproduct.product_id')
		// 	->where('fuel_receipt.status', '!=', 'voided')
		// 	->where('product.ptype', 'oilgas')
		// 	->whereBetween('fuel_receiptdetails.created_at', [$start.' 00:00:00', $stop.' 23:59:59'])
		// 	->select('product.name','product.systemid','receiptrefund.qty','receiptrefund.refund_amount')
		// 	->get();

		// $receipt_refund = DB::table('receipt')
		// 	->join('receiptrefund', 'receiptrefund.receipt_id', '=', 'receipt.id')
		// 	->where('receipt.status', '!=', 'voided')
		// 	->where('receipt.pump_no', '0')
		// 	->whereBetween('receipt.created_at', [$start.' 00:00:00', $stop.' 23:59:59'])
		// 	->select('receipt.systemid','receiptrefund.qty','receiptrefund.refund_amount')
		// 	->get();

        Log::debug('Sales: '.json_encode($product_details));

		// $total_refund = DB::table('receiptdetails')
		// 	->join('receiptproduct', 'receiptproduct.receipt_id', '=', 'receiptdetails.receipt_id')
		// 	->join('receipt', 'receiptproduct.receipt_id','=','receipt.id')
		// 	->join('receiptrefund', 'receiptrefund.receipt_id', '=', 'receipt.id')
		// 	->where('receipt.status', '!=', 'voided')
		// 	//->where('receiptproduct.product_id', '=', $product->id)
		// 	->whereBetween('receiptdetails.created_at', [$start.' 00:00:00', $stop.' 23:59:59'])
		// 	->sum('receiptrefund.refund_amount');

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
			->loadView('sales_report.fuel_sales_report_pdf', compact('product_details', 'requestValue' , 'location', 'refund', 'currency'));

        // download PDF file with download method
        // $pdf = PDF::loadHTML('<p>Hello World!!</p>');
        // return $pdf->stream();
        $pdf->getDomPDF()->setBasePath(public_path().'/');
        $pdf->getDomPDF()->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'allow_self_signed'=> true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
            ])
        );
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('SalesReport.pdf');
    }


    public function terminalDate(){
        $created_at = DB::table('terminal')->select('created_at')->first();
        return $created_at->created_at;
    }


    public function storeExcel(Request $request) {
        $comm_receipts = [];
        Log::info($request->date_excel);
        if ($request->date_excel)
        {
            $comm_receipts  = CommReceipt::with(["location","user"])
                ->whereBetween('created_at', [$request->date_excel." 00:00:00",$request->date_excel." 23:59:59"])->get();
        }else{
            $comm_receipts  = CommReceipt::with(["location","user"])->get();
        }

        $filename = time()."_fuel_usage.xlsx";
        Excel::store(new FuelUsageExport($comm_receipts), $filename,"excel_disk");

        return "exports/".$filename;
    }
}
