<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Receipt;
use App\Models\ReceiptDetails;
use App\Models\ReceiptRefund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class CommcabinetController extends Controller
{
    function commCabinet()
    {
        return view("comm_cabinet.comm_cabinet");
    }

    function listCommCabinet()
    {
        try {

            $data = [];
            return DataTables::of($data)->
            addIndexColumn()->
            make(true);


        } catch (\Exception $e) {
            Log::error([
                "Error" =>  $e->getMessage(),
                "File"  =>  $e->getFile(),
                "Line"  =>  $e->getLine()
            ]);

            return ["message" => $e->getMessage(), "error" => false];
        }
    }

    function commCabinetDetail()
    {

        try {
            $company = Company::first();
            $receipt = Receipt::first();
            $receiptdetails = ReceiptDetails::first();
            $refund = ReceiptRefund::first();
            return view("local_cabinet.commercial_receipt", compact("company","receipt","receiptdetails","refund"));

        } catch (\Exception $e) {
            return ["message" => $e->getMessage(), "error" => false];
        }
    }

}
