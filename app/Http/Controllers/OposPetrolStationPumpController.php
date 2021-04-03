<?php

namespace App\Http\Controllers;

use DB;
use Log;
use \App\Classes\PTS2;
use \App\Classes\UserData;
use App\Models\OgFuel;
use App\Models\product;
use Illuminate\Http\Request;
use App\Models\og_pumplog;
use Illuminate\Support\Facades\Auth;

class OposPetrolStationPumpController extends Controller
{
    public function __construct() {
       //$this->middleware('auth');
       // $this->middleware('CheckRole:ret');
    }


    public function getPumpNozzlesConfiguration($url) {
		if (empty($url)) $url = env('PTS_URL');
        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);
        $res = $pts2->get_pump_nozzles_configuration();

        //dump($res);

        $pts2->close_channel();
        return response()->json(['data' => $res]);
    }


    public function pumpGetTotals($pumpNo, $nozzle, $ipaddr) {
		$conn_fail = false;

		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);
        $res = $pts2->pump_get_totals($pumpNo, $nozzle);

        //dump($res);

        $pts2->close_channel();
        return response()->json(['data' => $res]);
    }


    public function pumpGetDisplayData($pumpNo, $ipaddr) {
		$conn_fail = false;

		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);
        $res = $pts2->pump_get_display_data($pumpNo);

        //dump($res);

        $pts2->close_channel();
        return response()->json(['data' => $res]);
    }


    public function setPumpNozzlesConfiguration(Request $request) {
		$pumpnozzles = $request->pumpnozzles;
		$ipaddr = $request->ipaddr;
		//$all = $request->all();

		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

//		Log::debug('setPumpNozzlesConfiguration:'.json_encode($all));
		Log::debug('setPumpNozzlesConfiguration:'.json_encode($pumpnozzles));

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);
        $res = $pts2->set_pump_nozzles_configuration($pumpnozzles);

        //dump($res);
		Log::debug('setPumpNozzlesConfiguration:'.json_encode($res));

        $pts2->close_channel();
        return response()->json(['data' => $res]);
    }


    public function getPtsNetworkSettings() {
        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'));
        $res = $pts2->get_pts_network_settings();

        //dump($res);

        $pts2->close_channel();
        return response()->json(['data' => $res]);
    }


	public function pumpCalibration($pumpNo, $ipaddr, $type='Volume',$dose=20,
		$nozzle=null,$price=null) {

		Log::debug('pumpCalibration: pumpNo='.$pumpNo.', ipaddr='.$ipaddr);

		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

        /* Note case sensitive: type=(Volume|Amount|FullTank) */
        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);

        $res = $pts2->pump_authorize($pumpNo, $type, $dose, $nozzle, $price);

        $pts2->close_channel();
        return response()->json(['data' => $res]);
    }


    public function pumpAuthorize($pumpNo,$type,$dose,$ipaddr,
		$nozzle=null,$fuelgradeid=null,$price=null){

        /* Note case sensitive: type=(Volume|Amount|FullTank) */
		if ($type == null) $type = 'Amount';

		Log::debug('pumpAuthorize:'.$pumpNo.', type='.$type.
			', dose='.$dose. ',nozzle='.$nozzle.
			', fuelgradeid='.$fuelgradeid.', price='.$price);

		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

		if ($nozzle == 'null') $nozzle = null;

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);

		/* Full tank processing */
		/*
		if (empty($dose) or $dose == 0) {
			$dose = 0;
			$type = 'FullTank';
		}
		*/

        $res = $pts2->pump_authorize($pumpNo, $type, $dose,
			$nozzle, $fuelgradeid, $price);

		Log::debug('pump_authorize:'.json_encode($res));

        $pts2->close_channel();
        return response()->json(['data' => $res]);
    }


    public function pumpGetStatus($pumpNo, $ipaddr) {
		$conn_fail = false;

		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);

        $res = $pts2->pump_get_status($pumpNo);

		if (!empty($res) || !empty($res->response) || !empty($ipaddr)) {

			if ($pumpNo==1 or $pumpNo==2) {
			//Log::debug('1. PTS '.$pumpNo.': '.json_encode($res));

			/*
			Log::debug('pumpGetStatus: ipaddr='.$ipaddr);
			Log::debug('pumpGetStatus: url   ='.$url);
			Log::debug('pumpGetStatus: env(PTS_USER)   ='.env('PTS_USER'));
			Log::debug('pumpGetStatus: env(PTS_PASSWD) ='.env('PTS_PASSWD'));
			*/
			}
		}

        $pts2->close_channel();

		/* Have to test for connection failures.
		 * We equate connection failures to pump offline */
		if ($res['response'] == null) {

			Log::debug('pumpGetStatus: NO RESPONSE from pump '.$pumpNo);

		 	/* We have connection failure! */
			$conn_fail = true;

			$res['response'] = array(
				'conn_fail' => $conn_fail,
				"Protocol" => "jsonPTS",
				"Packets" => array(array(
					"Id" => 1,
					"Type" => "PumpOfflineStatus",
					"Data" => array(
						"Pump" => $pumpNo,
						"User" => "admin"
					)
				)
			));
		}

		if (!empty($res->response)) {
			Log::debug('2. PTS '.$pumpNo.': '.json_encode($res));
		}

        return response()->json([ 'data' => $res ]);
    }


    public function pumpCloseTransaction($pumpNo, $transaction) {
		
		$url = "http://".env('PTS_IPADDR')."/jsonPTS";

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'));
        $res = $pts2->pump_close_transaction($pumpNo, $transaction, $url);

        //dump($res);

        $pts2->close_channel();
        return response()->json([ 'data' => $res ]);
    }

    public function getFuelGradesConfiguration() {
        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'));
        $res = $pts2->get_fuel_grades_configuration();

        //dump($res);

        $pts2->close_channel();
        return response()->json(['data' => $res]);
    }


    public function setFuelGradesConfiguration(Request $request) {
		$fuelgrades = $request->fuelgrades;
		$ipaddr = $request->ipaddr;
		$all = $request->all();

		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

		Log::debug('setFuelGradesConfiguration:'.json_encode($all));
		Log::debug('setFuelGradesConfiguration:'.json_encode($fuelgrades));

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);
        $res = $pts2->set_fuel_grades_configuration($fuelgrades);

        Log::debug('set_fuel_grades_configuration:'.json_encode($res));

        $pts2->close_channel();
        return response()->json(['data' => $res]);
    }


    public function getUniqueIdentifier($ipaddr) {
		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);
        $res = $pts2->get_unique_identifier();
        $pts2->close_channel();
		//dump($res);
        return response()->json(['data' => $res]);
    }

    public function getFirmwareInformation($ipaddr) {
		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);
        $res = $pts2->get_firmware_information();
        $pts2->close_channel();
		//dump($res);
        return response()->json(['data' => $res]);
    }

    public function getSdInformation($ipaddr) {
		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);
        $res = $pts2->get_sd_information();
        $pts2->close_channel();
		//dump($res);
        return response()->json(['data' => $res]);
    }

    public function getBatteryVoltage($ipaddr) {
		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);
        $res = $pts2->get_battery_voltage();
        $pts2->close_channel();
		//dump($res);
        return response()->json(['data' => $res]);
    }

    public function getPumpsConfiguration($ipaddr) {
		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);
        $res = $pts2->get_pumps_configuration();
        $pts2->close_channel();
		//dump($res);
        return response()->json(['data' => $res]);
    }

    public function setPumpsConfiguration(Request $request) {
		$ports = $request->ports;
		$pumps = $request->pumps;
		$ipaddr = $request->ipaddr;
		$all = $request->all();

		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

		Log::debug('setPumpsConfiguration:'.json_encode($ports));
		Log::debug('setPumpsConfiguration:'.json_encode($pumps));

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);
        $res = $pts2->set_pumps_configuration($ports, $pumps);

        //dump($res);

        $pts2->close_channel();
        return response()->json(['data' => $res]);
    }



    public function getDatetime() {
        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'));
        $res = $pts2->get_datetime();
        $pts2->close_channel();
		//dump($res);
        return response()->json(['data' => $res]);
    }


    public function setDatetime($datetime) {
        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'));
        $res = $pts2->set_datetime($datetime);
        $pts2->close_channel();
		//dump($res);
        return response()->json(['data' => $res]);
    }


    public function setPtsNetworkSettings(Request $request) {
		$ipaddress  = $request->input('ipaddress');
		$netmask    = $request->input('netmask');
		$gateway    = $request->input('gateway');
		$http_port  = $request->input('http_port');
		$https_port = $request->input('https_port');
		$dns1       = $request->input('dns1');
		$dns2       = $request->input('dns2');

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'));
		// Don't allow network settings to be modified
		/*
        $res = $pts2->set_pts_network_settings($ipaddress, $netmask, $gateway,
			$http_port, $https_port, $dns1, $dns2);
        $pts2->close_channel();
		*/

        return response()->json(['data' => $res]);
    }


    public function saveOgPumpLog(Request $request) {
		$user_data = new UserData();

        $ogPumpLog = new og_pumplog();
        $ogPumpLog->controller_id = $request->input('controllerId');
        $ogPumpLog->pump = $request->input('pumpNo');
        $ogPumpLog->nozzle = $request->input('nozzle');
        $ogPumpLog->product = $request->input('product');
        $price = (float)str_replace(",", "", $request->input('price'));
        $ogPumpLog->price = $price * 100;
        $ogPumpLog->transaction = $request->input('transaction');
        $amount = (float)str_replace(",", "", $request->input('amount'));
        $ogPumpLog->amount = $amount * 100;
        $ogPumpLog->volume = $request->input('volume');
        $ogPumpLog->user = $request->input('user');
		$ogPumpLog->merchant_id = $user_data->company_id();
        $ogPumpLog->save();
		\Log::info( [
			"REQUEST FROM HARDWARE" => $request->all()
		] );
    }


    public function pumpStopFilling($pumpNo, $ipaddr) {
		Log::debug('***** PS pumpStopFilling('.$pumpNo.') *****');

		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);
        $res = $pts2->pump_stop($pumpNo);
        $pts2->close_channel();
        return response()->json([ 'data' => $res ]);
    }


    public function pumpSuspendFilling($pumpNo, $ipaddr) {
		Log::debug('***** PS pumpSuspendFilling('.$pumpNo.') *****');

		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);
        $res = $pts2->pump_suspend($pumpNo);
        $pts2->close_channel();
        return response()->json([ 'data' => $res ]);
    }


    public function pumpResumeFilling($pumpNo, $ipaddr) {
		Log::debug('***** PS pumpResumeFilling('.$pumpNo.') *****');

		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);
        $res = $pts2->pump_resume($pumpNo);
        $pts2->close_channel();
        return response()->json([ 'data' => $res ]);
    }

    public function pumpCancelAuthorize($pumpNo, $ipaddr) {
		Log::debug('***** PS pumpCancelAuthorize('.$pumpNo.') *****');

		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);
        $res = $pts2->pump_emergency_stop($pumpNo);
        $pts2->close_channel();
        return response()->json([ 'data' => $res ]);
    }

    public function pumpDoneFilling($pumpNo, $ipaddr) {
		Log::debug('***** PS pumpDoneFilling('.$pumpNo.') *****');

		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);
        $res = $pts2->pump_emergency_stop($pumpNo);
        $pts2->close_channel();
        return response()->json([ 'data' => $res ]);
    }

    public function pumpEmergencyStop($pumpNo, $ipaddr) {
		Log::debug('***** PS pumpEmergencyStop('.$pumpNo.') *****');

		if (empty($ipaddr)) {
			$url = env('PTS_URL');
		} else {
			$url = "http://".$ipaddr."/jsonPTS";
		}

        $pts2 = new PTS2(env('PTS_USER'), env('PTS_PASSWD'), $url);
		$res = $pts2->pump_emergency_stop($pumpNo);
        $pts2->close_channel();
        return response()->json([ 'data' => $res ]);
    }

	/* This is to match a product by the nozzle. We need to make sure that
	 * pump_no is also differentiated by the controller and merchant */
	public function pumpProductByNozzle($pump_no, $nozzle_no) {

		$nozzle_no = json_decode($nozzle_no);

		if (is_array($nozzle_no))
			$nozzle_no = $nozzle_no[0];

		$query2 = "
			SELECT
				p.id,
				p.name,
				p.systemid,
				prd.id as ogFuel_id,
				p.thumbnail_1
			FROM
				product p,
				prd_ogfuel prd,
				local_pump op,
				local_controller oc,
				local_pumpnozzle nz
			WHERE
				op.pump_no = ".$pump_no." AND
				nz.nozzle_no = ".$nozzle_no." AND
				nz.pump_id = op.id AND
				nz.ogfuel_id = prd.id AND
				op.controller_id = oc.id AND
				prd.product_id = p.id";

        $result2 = DB::select(DB::raw($query2));
		//Log::debug('MA2 query2='.$query2);
		//Log::debug('MA2 result2='.json_encode($result2));

		$pid = null;
		$pname = null;

		if (!empty($result2)) {
			$pid 		= $result2[0]->id;
			$pname 		= $result2[0]->name;
			$psystemid 	= $result2[0]->systemid;
			$thumb		= $result2[0]->thumbnail_1;	
			$thumbnail	= asset("/images/product/$psystemid/thumb/$thumb");
			//Log::debug('MA2 pid='.$pid);
			//Log::debug('MA2 pname='.$pname);

			$price = (float) number_format( app('App\Http\Controllers\LocalFuelController')->
				getControllerPrice($result2[0]->ogFuel_id),2);

			if ($price == 0) {
				$price = (float) number_format( app('App\Http\Controllers\LocalFuelController')->
					getPrice($result2[0]->ogFuel_id),2);
			}
		}
        return response()->json([
			'pid' => $pid,
			'product' => $pname,
			'thumbnail'	=>	$thumbnail ?? '',
			'price'		=>	$price
		]);
	}


	public function getProductByName($name) {
		$product = null;
		$raw = product::where('name',$name)->get();

		if (!empty($raw) and !empty($raw[0])) {
			$product = $raw[0];
		}

		Log::debug('***** getProductByName('.$name.') *****');
		Log::debug(json_encode($product));

        return response()->json([ 'product' => $product ]);
	}
}
