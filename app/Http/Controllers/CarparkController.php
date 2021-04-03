<?php

namespace App\Http\Controllers;

use App\Models\CarparklotSettingKwh;
use App\Models\EvReceipt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Classes\SystemID;
use App\Models\CarPark;
use App\Models\CarParkLotSetting;
use App\Models\CarparkOper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Terminal;
use App\Models\Company;

class CarparkController extends Controller
{
    function carPark()
    {
        try {

            $test = null;

            return view('carpark.carpark_setting',
                compact('test'));

        } catch (\Exception $e) {
            Log::error([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            abort(404);
        }
    }


    function listCarPark()
    {
        try {

            $data = CarPark::all();
            return DataTables::of($data)->
            addIndexColumn()->
            editColumn('lot_no', function ($data) {
                $lot_no = $data;
                return $lot_no;
            })->
            editColumn('rate', function ($data) {
                $rate = $data;
                return $rate;
            })->
            editColumn('kwh', function ($data) {
                $rate = $data;
                return $rate;
            })->
            addColumn('action', function ($row) {
                $btn = '<a  href="javascript:void(0)" onclick="deleteMe(' . $row->id . ')" data-row="' . $row->id . '" class="delete"> <img width="25px" src="images/redcrab_50x50.png" alt=""> </a>';
                return $btn;
            })->addColumn('bluecrab', function ($row) {
                $btn = '<a  href="javascript:void(0)" onclick="" data-row="' . $row->id . '" class=""> <img width="25px" src="images/bluecrab_50x50.png" alt=""> </a>';
                return $btn;
            })->rawColumns(['bluecrab', 'action'])
                ->make(true);


        } catch (\Exception $e) {
            Log::error([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);

            return ["message" => $e->getMessage(), "error" => false];
        }
    }

    function listCarParkOpera()
    {
        try {

            $data = CarPark::with("carparkoper")->get();
            return DataTables::of($data)->
            addIndexColumn()->
            make(true);


        } catch (\Exception $e) {
            Log::error([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);

            return ["message" => $e->getMessage(), "error" => false];
        }
    }


    function save(Request $request)
    {
        try {
            $systemid = new SystemID("carparklot");
            $carparksetting = CarParkLotSetting::orderByDesc("id")->first();
            $carparksettingkwh = CarparklotSettingKwh::orderByDesc("id")->first();
            $allCp = CarPark::all();
            $carpark = CarPark::create([
                "systemid" => $systemid,
                "lot_no" => (sizeof($allCp) + 1),
                "rate" => $carparksetting ? $carparksetting->default_rate : 0,
                "kwh" => $carparksettingkwh ? $carparksettingkwh->default_kwh : 0
            ]);

            CarparkOper::create([
                "carparklot_id" => $carpark->id,
                "in" => null,
                "out" => null,
                "amount" => 0,
                "payment" => 0,
            ]);

            return ["data" => $carpark, "error" => false];

        } catch (\Exception $e) {
            Log::error([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            return ["message" => $e->getMessage(), "error" => false];
        }
    }


    function updateValue(Request $request)
    {
        Log::info($request->all());
        if ($request->element === "default_rate") {
            $data = [
                "default_rate" => $request->value,
            ];

            $carParkLotSetting = CarParkLotSetting::create($data);
            return ["data" => $carParkLotSetting, "error" => false, "other" => "default_rate"];

        } else {

            if ($request->element === "default_kwh") {
                $data = [
                    "default_kwh" => $request->value,
                ];

                $carParkLotSetting = CarparklotSettingKwh::create($data);
                return ["data" => $carParkLotSetting, "error" => false, "other" => "default_kwh"];
            }else{
                $data = [
                    $request->key => $request->value,
                ];

                $prdOpen = CarPark::where("id", $request->element)->update($data);
                return ["data" => $prdOpen, "error" => false, "other" => $request->key];
            }


        }

    }


    function carParkLanding()
    {
        try {

            $company = Company::first();

            Log::debug('carParkLanding: company=' . json_encode($company));

            $currency = DB::table('currency')->
            where('id', $company->currency_id)->
            orderBy('code')->get()->first();

            // Protect against NULL currency
            if (empty($currency)) {
                $currency = DB::table('currency')->
                where('code', 'MYR')->get()->first();
            }

            Log::debug('carParkLanding: currency=' . json_encode($currency));
            $terminal_all_value = $this->getTerminalInfo();
            $terminal = (object)array('currency' => $currency->code);

            $carparkOperas = $this->getCustomCarParkOpera();


            return view('carpark.carpark_landing',
                compact('carparkOperas', 'terminal','terminal_all_value'));

        } catch (Exception $e) {
            Log::error([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            abort(404);
        }
    }

    function getTerminalInfo(){
        $client_ip = request()->ip();
        $terminal_all_value = DB::table('terminal')->where('client_ip', $client_ip)->first();
        return $terminal_all_value;
    }


    function getCustomCarParkOpera()
    {
        $carparkOperas = CarPark::with("carparkoper")->get();
        $carparkOperasNew = [];
        foreach ($carparkOperas as $opera) {
            $hours = 0;
            $amount = 0;
            if (($opera->carparkoper->in != null &&
                $opera->carparkoper->out != null)) {
                $date1 = new \DateTime($opera->carparkoper->in);
                $date2 = new \DateTime($opera->carparkoper->out);

                $diff = $date2->diff($date1);
                $min = 0;
                if ($diff->i > 0 || $diff->s > 0) {
                    $min = 1;
                }
                $hours = $diff->h;
                $hours = $hours + ($diff->days * 24) + $min;
                $amount = $hours * $opera->rate;
            }
            $opera["hours"] = $hours;
            $opera["amount"] = $amount;
            array_push($carparkOperasNew, $opera);
        }

        $carparkOperas = $carparkOperasNew;
        return $carparkOperas;
    }


    function loadDefaultRate()
    {
        try {

            $data = CarParkLotSetting::orderByDesc("id")->first();
            $carparksettingkwh = CarparklotSettingKwh::orderByDesc("id")->first();

            return ["data" => $data,"carparksettingkwh"=>$carparksettingkwh];

        } catch (\Exception $e) {
            Log::error([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            abort(404);
        }
    }

    function lotDelete(Request $request)
    {

        try {

            CarPark::find($request->id)->delete();
            CarparkOper::where("carparklot_id", $request->id)->delete();
            return ["message" => "delete done", "Error" => false];

        } catch (\Exception $e) {
            Log::error([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            abort(404);
        }
    }


    function actionStatus(Request $request)
    {
        try {
            $carparkopera = CarparkOper::find($request->id);
            if ($carparkopera->in == null) {
                $carparkopera->in = now();
            } else {
                $carparkopera->out = now();
                $carparkopera->amount = intval($request->int_amount);
            }
            $carparkopera->save();
            $carparkOperas = $this->getCustomCarParkOpera();

            return view("carpark.carparklot_table", compact("carparkOperas"));

        } catch (\Exception $e) {
            Log::error([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            abort(404);
        }
    }

    function getRounding(Request $request)
    {
        $rounding = $this->round_amount($request->amount);
        if ($rounding < 0) {
            return ["number" => $this->round_amount($request->amount), "text_number" => "-0.0" . strval($this->round_amount($request->amount))[1]];

        } else {
            return ["number" => $this->round_amount($request->amount), "text_number" => "0.0" . strval($this->round_amount($request->amount))];

        }
    }

    function round_amount($num)
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


    function setEnter(Request $request)
    {

        $systemid = new SystemID("ev_receipt");

        $user_id = Auth::user()->id;
        $user_currency_id = Auth::user()->currency_id;

        $company = Company::first();

        $terminal = DB::table('terminal')->first();
        $ev_receipt = DB::table("ev_receipt")->insertGetId([
            "systemid" => $systemid,
            "service_tax" => $request->service_tax,
            "payment_type" => $request->payment_type,
            "terminal_id" => $terminal->id,
            "staff_user_id" => $user_id,
            "company_id" => $company->id,
            "company_name" => $company->name,
            "currency" => $user_currency_id,
            "round" => $request->round,
            "remark" => " ",
            "pump_no" => 0,
            "pump_id" => 0,
            "description" => $request->description,
            "hours" => $request->hours,
            "rate" => $request->rate,
            "myr" => $request->myr,
            "itemAmount" => $request->itemAmount,
            "tax" => $request->tax,
            "total" => $request->total,
            "cash_received" => ($request->payment_type == "cash" ? $request->cash_received : 0),

        ]);

        $carparkopera = CarparkOper::find(intval($request->carparkoper_id));
        $carparkopera->status = "paid";
        $carparkopera->save();
        DB::table("evreceiptcarparklot")->insert([
            "evreceipt_id" => $ev_receipt,
            "carparklot_id" => intval($request->carparklot_id),
            "created_at" => now()
        ]);

        $carparkOperas = $this->getCustomCarParkOpera();

        return view("carpark.carparklot_table", compact("carparkOperas"));
    }


}
