<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\EvreceiptCarpark;
use App\Models\ReceiptDetails;
use App\Models\ReceiptRefund;
use App\Models\Terminal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Location;
use Yajra\DataTables\DataTables;

class EVReceiptController extends Controller
{

    function evList()
    {

        try {
            $ev = [];

            return view("ev_receipt.ev_receiptlist", compact("ev"));

        } catch (\Exception $e) {
            return ["message" => $e->getMessage(), "error" => false];
        }
    }

    function evListData()
    {

        try {
            $data = EvreceiptCarpark::with("evreceipt","carparklot.carparkoper")->get();
            Log::info($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('evreceipt', function ($data) {
                    $evreceipt = $data->evreceipt->systemid;
                    $id = $data->id;
                    return ["evreceipt"=>$evreceipt,"id"=>$id,];
                })
                ->editColumn('total', function ($data) {
                    $evreceipt = $data->evreceipt->total;
                    return $evreceipt;
                })
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

    function evReceiptDetail(Request $request)
    {

        try {
            $company = Company::first();
            $receipt =  EvreceiptCarpark::with("evreceipt.staff_user","carparklot.carparkoper")->whereId($request->id)->first();
            $terminal = Terminal::first();
            $location = \App\Models\Location::first();
            $receiptdetails = ReceiptDetails::first();
            $refund = ReceiptRefund::first();
            return view("ev_receipt.ev_receipt", compact("company","receipt","receiptdetails","refund","terminal","location"));

        } catch (\Exception $e) {
            return ["message" => $e->getMessage(), "error" => false];
        }
    }


}
