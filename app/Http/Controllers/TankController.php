<?php

namespace App\Http\Controllers;

use App\Classes\PTS2;
use App\Classes\SystemID;
use App\Models\PrdOgfuel;
use App\Models\Product;
use App\Models\Tank;
use App\Models\TankMon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class TankController extends Controller
{
    function tankmonitoring() {
        try {

            $test = null;

            return view('tank.tankmonitoring',
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


    function tankList() {
        try {
            $tanks = Tank::with(["product", "user"])->select('*')->get();
            return DataTables::of($tanks)
                ->addIndexColumn()
                ->editColumn('tank_no', function ($data) {
                    $tank_no = $data;
                    return $tank_no;
                })
                ->editColumn('height', function ($data) {
                    $height = $data;
                    return $height;
                })
                ->editColumn('product', function ($data) {
                    $product = $data;
                    return $product;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a  href="javascript:void(0)" onclick="deleteMe(' . $row->id . ')" data-row="' . $row->id . '" class="delete"> <img width="25px" src="images/redcrab_50x50.png" alt=""> </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);

        } catch (\Exception $e) {
            return ["message" => $e->getMessage(), "error" => false];
        }
    }


    function tankMonitoringList() {
        try {
            $tankMonitorings = TankMon::with(["tank.product"])->
				orderByDesc("id")->get();

            Log::info($tankMonitorings);

            return DataTables::of($tankMonitorings)
                ->addIndexColumn()
                ->editColumn('tank', function ($data) {
                    $tank = $data;
                    return $tank;
                })
                ->editColumn('product', function ($data) {
                    $product = $data;
                    return $product;
                })
                ->make(true);


        } catch (\Exception $e) {
            return ["message" => $e->getMessage(), "error" => false];
        }
    }


    function tankSave(Request $request) {
        try {
            // WARNING: Hardcoding location_id=1
            $systemid = SystemID::openitem_system_id(1);
            $tank = Tank::create(["systemid" => $systemid,
                "tank_no" => null,
                "height" => null,
                "type" => "direct",
                "max_capacity" => $request->max_capacity,]);

            $tankmon = TankMon::create(["tank_id" => $tank->id,
                "tank_filling_pct" => 0,
                "product_mm" => 0,
                "water_mm" => 0,
                "temperature_c" => 0,
                "product_l" => 0,
                "water_l" => 0,
                "ullage_l" => 0,
                "tc_volume" => 0,
            ]);
            return ["data" => $tankmon, "error" => false];

        } catch (\Exception $e) {
            return ["message" => $e->getMessage(), "error" => false];
        }
    }


    function generateRandomString($length = 10) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    function updateKey(Request $request) {
        $data = [
            $request->key => $request->value,
        ];

        $tank = Tank::where("id", $request->element)->update($data);
        return ["data" => $tank, "error" => false];
    }


    function products(Request $request) {
        $tank = $request->tank;
        $products = PrdOgfuel::with("product")->
			whereHas("product", function ($query) {
            $query->where("ptype", "oilgas");
        })->get();

        return view("tank.product_response",
            compact("products", "tank"));

    }


    function chooseProduct(Request $request) {
        $tank = $request->tank;
        $product = $request->product;
        $tank = Tank::where("id", $tank)->
			update(["product_id" => $product]);

        return ["data" => $tank, "error" => false];
    }


    function delete(Request $request) {
        Tank::find($request->id)->delete();;
        return ["data" => [], "error" => false];
    }


    function tankMonitoringInterval(Request $request) {
        $tanks = Tank::all();
        foreach ($tanks as $tank) {

        }
        return [$tanks];
    }


    public function setTanksConfiguration(Request $request) {
		$tanks = $request->tanks;
		$ipaddr = $request->ipaddr;
		//$all = $request->all();

		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

		Log::debug('setTanksConfiguration:'.json_encode($tanks));

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);
        $res = $pts2->set_tanks_configuration($tanks);

        //dump($res);
		Log::debug('setTanksConfiguration:'.json_encode($res));

        $pts2->close_channel();
        return response()->json(['data' => $res]);
    }


    public function setProbesConfiguration(Request $request) {
		$ports  = $request->ports;
		$probes = $request->probes;
		$ipaddr = $request->ipaddr;
		//$all = $request->all();

		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

		Log::debug('setProbesConfiguration: ports ='.json_encode($ports));
		Log::debug('setProbesConfiguration: probes='.json_encode($probes));

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);
        $res = $pts2->set_probes_configuration($ports, $probes);

        //dump($res);
		Log::debug('setProbesConfiguration:'.json_encode($res));

        $pts2->close_channel();
        return response()->json(['data' => $res]);
    }


    public function getTanksConfiguration($ipaddr) {
		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);
        $res = $pts2->get_tanks_configuration();
        $pts2->close_channel();
		//dump($res);
        return response()->json(['data' => $res]);
    }


    public function getProbesConfiguration($ipaddr) {
		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);
        $res = $pts2->get_probes_configuration();
        $pts2->close_channel();
		//dump($res);
        return response()->json(['data' => $res]);
    }


    public function probeGetTankVolumeForHeight($probe_no, $height, $ipaddr) {
		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);
		$res = $pts2->probe_get_tank_volume_for_height($probe_no, $height);
        $pts2->close_channel();
        return response()->json([ 'data' => $res ]);
    }


    public function probeGetMeasurements($probe_no, $ipaddr) {
		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);
		$res = $pts2->probe_get_measurements($probe_no );
        $pts2->close_channel();
        return response()->json([ 'data' => $res ]);
    }



}
