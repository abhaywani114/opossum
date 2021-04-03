<?php

namespace App\Http\Controllers;

use Log;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Terminal;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\SetupController;

class AccessController extends Controller
{

    public function __construct()
    {
    }

    protected $landing_page = '/';

    public function landing()
    {
        $company = Company::first();

        $server_ip = $_SERVER['SERVER_ADDR'] ?? $_SERVER['REMOTE_ADDR'];
        $client_ip = request()->ip();

        $terminal = DB::table('terminal')->where('client_ip', $client_ip)->first();
        /*
         * Add here && false to access fuel page
         * LIKE: $isServerEnd = $server_ip == $client_ip && false;
         */

        Log::info([
            "server_ip" => $server_ip,
            "client_ip" => $client_ip
        ]);

        $isServerEnd = $server_ip == $client_ip;

        $isLocationActive = DB::table('lic_locationkey')->
			where('has_setup', 1)->first();

		$detectHardware = app('App\Http\Controllers\SetupController')->getMacLinux();
		Log::debug('detectHardware='.json_encode($detectHardware));


		$verifyHardware = DB::table('serveraddr')->
			where([
				'ip_addr'	=>	$server_ip,
				'hw_addr'	=>	$detectHardware	
			])->first();

		Log::info([
			'ip_addr'			=>	$server_ip,
			'hw_addr'			=>	$detectHardware,
			'verifyHardware'	=>	!empty($verifyHardware)
		]);

    	//    $isTerminalActive = DB::table('lic_terminalkey')->
      	//  where('has_setup', 1)->first();
	
		$isTerminalActive = DB::table('lic_terminalkey')->
			where([
				'terminal_id' => $terminal->id ?? 0 ,
				'has_setup' => 1
			])->first();

        Log::debug('isLocationActive='.json_encode($isLocationActive));
        Log::debug('isTerminalActive='.json_encode($isTerminalActive));

        if ($isServerEnd || empty($isLocationActive)) {

            if (empty($isLocationActive)) {
                $return = view('oceania_svr.landing.login',
					compact('company', 'isLocationActive',
					'isTerminalActive', 'isServerEnd', 'verifyHardware'));

            } elseif (!empty(Auth::User())) {
                $return = view('oceania_svr.landing.landing',
					compact('verifyHardware'));

            } else {
                $return = view('oceania_svr.landing.login',
					compact('company', 'isLocationActive', 'isTerminalActive',
					'isServerEnd', 'verifyHardware'));
            }
        } else {
            $pump_hardware = DB::table('local_pump')->
            join('local_controller', 'local_controller.id', '=',
				'local_pump.controller_id')->
            select('local_controller.ipaddress', 'local_pump.pump_no')->
            get()->unique('pump_no');

            $nozzleFuelData = DB::table('local_pumpnozzle')->
            join('local_pump', 'local_pump.id', '=', 'local_pumpnozzle.pump_id')->
            join('local_controller', 'local_controller.id', '=', 'local_pump.controller_id')->
            join('prd_ogfuel', 'prd_ogfuel.id', '=', 'local_pumpnozzle.ogfuel_id')->
            whereNull('local_pumpnozzle.deleted_at')->
            whereNull('local_pump.deleted_at')->
            whereNull('local_controller.deleted_at')->
            select('prd_ogfuel.product_id', 'local_pump.pump_no', 'local_pumpnozzle.nozzle_no')->
            get();

            $productData = DB::table('product')->
            whereptype('oilgas')->
            get();

            $return = view('landing.landing', compact(
                'company',
                'pump_hardware',
                'productData',
                'terminal',
                'nozzleFuelData',
                'isLocationActive',
                'isServerEnd',
				'verifyHardware',
                'isTerminalActive'
            ));
        }
        Log::debug('return='.json_encode($return));
        return $return ?? '';
    }


    public function landingTemp()
    {
        $company = $isLocationActive = Company::first();

        $client_ip = request()->ip();
        $terminal = $isTerminalActive = DB::table('terminal')->
			where('client_ip', $client_ip)->first();

        $isServerEnd = false;

        if(env('ONLY_ONE_HOST')){
            $isTerminalActive = true;
		} else {
			$isTerminalActive = DB::table('lic_terminalkey')->
				where([
					'terminal_id' => $terminal->id ?? 0 ,
					'has_setup' => 1
				])->first();
		}

        $pump_hardware = DB::table('local_pump')->
        join('local_controller', 'local_controller.id', '=', 'local_pump.controller_id')->
        select('local_controller.ipaddress', 'local_pump.pump_no')->
        get()->unique('pump_no');

        $nozzleFuelData = DB::table('local_pumpnozzle')->
        join('local_pump', 'local_pump.id', '=', 'local_pumpnozzle.pump_id')->
        join('local_controller', 'local_controller.id', '=', 'local_pump.controller_id')->
        join('prd_ogfuel', 'prd_ogfuel.id', '=', 'local_pumpnozzle.ogfuel_id')->
        whereNull('local_pumpnozzle.deleted_at')->
        whereNull('local_pump.deleted_at')->
        whereNull('local_controller.deleted_at')->
        select('prd_ogfuel.product_id', 'local_pump.pump_no', 'local_pumpnozzle.nozzle_no')->
        get();

        $productData = DB::table('product')->where('ptype','oilgas')->get();
		
		$detectHardware = app('\App\Http\Controllers\SetupController')->getMacLinux();
		$verifyHardware = DB::table('serveraddr')->
			where([
				'ip_addr'	=>	$_SERVER['SERVER_ADDR'] ?? $_SERVER['REMOTE_ADDR'],
				'hw_addr'	=>	$detectHardware	
			])->first();

        return view('landing.landing', compact(
            'company',
            'pump_hardware',
            'productData',
            'terminal',
            'nozzleFuelData',
            'isLocationActive',
            'isServerEnd',
			'verifyHardware',
            'isTerminalActive'
        ));

	}

 public function one_host_landing_h2()
    {
        
        $company = $isLocationActive = Company::first();

        $client_ip = request()->ip();
        $terminal = $isTerminalActive = DB::table('terminal')->
            where('client_ip', $client_ip)->first();
        $isServerEnd = false;

        if(env('ONLY_ONE_HOST')){
            $isTerminalActive = true;
        } else {
        
            $isTerminalActive = DB::table('lic_terminalkey')->
                where([
                    'terminal_id' => $terminal->id ?? 0 ,
                    'has_setup' => 1
                ])->first();
        }

        $ONLY_ONE_HOST = true;
        Log::info([ 
            'terminal_id' => $terminal->id ?? null,
            'isTerminalActive' => !empty($isTerminalActive)
        ]);

        $pump_hardware = DB::table('local_pump')->
        join('local_controller', 'local_controller.id', '=', 'local_pump.controller_id')->
        select('local_controller.ipaddress', 'local_pump.pump_no')->
        get()->unique('pump_no');

        $nozzleFuelData = DB::table('local_pumpnozzle')->
        join('local_pump', 'local_pump.id', '=', 'local_pumpnozzle.pump_id')->
        join('local_controller', 'local_controller.id', '=', 'local_pump.controller_id')->
        join('prd_ogfuel', 'prd_ogfuel.id', '=', 'local_pumpnozzle.ogfuel_id')->
        whereNull('local_pumpnozzle.deleted_at')->
        whereNull('local_pump.deleted_at')->
        whereNull('local_controller.deleted_at')->
        select('prd_ogfuel.product_id', 'local_pump.pump_no', 'local_pumpnozzle.nozzle_no')->
        get();

        $productData = DB::table('product')->where('ptype','oilgas')->get();
        
        $detectHardware = app('\App\Http\Controllers\SetupController')->getMacLinux();
        $verifyHardware = DB::table('serveraddr')->
            where([
                'ip_addr'   =>  $_SERVER['SERVER_ADDR'] ?? $_SERVER['REMOTE_ADDR'],
                'hw_addr'   =>  $detectHardware 
            ])->first();

        $fuel_grade_string = app('\App\Http\Controllers\LocalFuelController')->
            fuelgradesConfig();

        Log::debug('$fuel_grade_string='.json_encode($fuel_grade_string));

        return view('h2.h2_landing', compact(
            'company',
            'pump_hardware',
            'productData',
            'terminal',
            'nozzleFuelData',
            'isLocationActive',
            'isServerEnd',
            'verifyHardware',
            'isTerminalActive',
            'fuel_grade_string',
            'ONLY_ONE_HOST'
        ));
    }


    public function one_host_landing()
    {
        $company = $isLocationActive = Company::first();
        //$company_currency = Company::latest('id')->first()->currency_id;
        $currencyarr = DB::table('currency')->where('id',$company->currency_id)->orderBy('code')->get()->first();
        $currency = $currencyarr->code ?? 'MYR';

        $client_ip = request()->ip();
        $terminal = $isTerminalActive = DB::table('terminal')->
			where('client_ip', $client_ip)->first();
        $isServerEnd = false;

        if(env('ONLY_ONE_HOST')){
            $isTerminalActive = true;
		} else {
		
			$isTerminalActive = DB::table('lic_terminalkey')->
				where([
					'terminal_id' => $terminal->id ?? 0 ,
					'has_setup' => 1
				])->first();
		}

		$ONLY_ONE_HOST = true;
		Log::info([ 
			'terminal_id' => $terminal->id ?? null,
			'isTerminalActive' => !empty($isTerminalActive)
		]);

        $pump_hardware = DB::table('local_pump')->
        join('local_controller', 'local_controller.id', '=', 'local_pump.controller_id')->
        select('local_controller.ipaddress', 'local_pump.pump_no')->
        get()->unique('pump_no');

        $nozzleFuelData = DB::table('local_pumpnozzle')->
        join('local_pump', 'local_pump.id', '=', 'local_pumpnozzle.pump_id')->
        join('local_controller', 'local_controller.id', '=', 'local_pump.controller_id')->
        join('prd_ogfuel', 'prd_ogfuel.id', '=', 'local_pumpnozzle.ogfuel_id')->
        whereNull('local_pumpnozzle.deleted_at')->
        whereNull('local_pump.deleted_at')->
        whereNull('local_controller.deleted_at')->
        select('prd_ogfuel.product_id', 'local_pump.pump_no', 'local_pumpnozzle.nozzle_no')->
        get();

		$productData = DB::table('product')->where('product.ptype','oilgas')->
			join('prd_ogfuel','prd_ogfuel.product_id', 'product.id')->
			select('product.*','prd_ogfuel.id as og_id')->get();

		$productData->map(function ($f) {
			$f->price = app('\App\Http\Controllers\LocalFuelController')->
			   getControllerPrice($f->og_id);	
		});
		
		$detectHardware = app('\App\Http\Controllers\SetupController')->getMacLinux();
		$verifyHardware = DB::table('serveraddr')->
			where([
				'ip_addr'	=>	$_SERVER['SERVER_ADDR'] ?? $_SERVER['REMOTE_ADDR'],
				'hw_addr'	=>	$detectHardware	
			])->first();

		$fuel_grade_string = app('\App\Http\Controllers\LocalFuelController')->
			fuelgradesConfig();

		Log::debug('$fuel_grade_string='.json_encode($fuel_grade_string));

        return view('landing.landing', compact(
            'currency',
            'company',
            'pump_hardware',
            'productData',
            'terminal',
            'nozzleFuelData',
            'isLocationActive',
			'isServerEnd',
			'verifyHardware',
			'isTerminalActive',
			'fuel_grade_string',
			'ONLY_ONE_HOST'
        ));
    }

    public function authorizeUser(Request $request)
    {
        $user = User::where('access_code', $request->access_code)->first();
        if ($user) {
            $useru = User::find($user->id);
            $useru->last_login = now();
            $useru->save();
            Auth::loginUsingId($user->id);

            if ($useru->status != 'active' && env('ONLY_ONE_HOST') == true)
                $return = ['login_error' => 'Unable to login, user is inactive'];
            else
                $return = ["landing" => url($this->landing_page)];

        } else {
            $return = false;
        }

        return $return;
    }


    public function uPLogin(Request $request)
    {
		Log::debug('uPLogin: all()='.json_encode($request->all()));

        $server_ip = $_SERVER['SERVER_ADDR'] ?? $_SERVER['REMOTE_ADDR'];

        $request->session()->flash('form', 'login');

        $credentials = $request->only('email', 'password');
		
		$client_ip = request()->ip();
        $terminal = DB::table('terminal')->
			where('client_ip', $client_ip)->first();


        if (Auth::attempt($credentials)) {
            // Authentication passed...
            // return redirect()->intended($this->landing_page);
            $useru = User::find(Auth::id());
            $useru->last_login = now();
            $useru->save();
        }

        if (!empty($useru)) {

            Log::debug('Hosting: '.$request->hosting);
            if ($useru->status != 'active') {
                Auth::logout();
                $return = ['login_error' =>
					'Unable to login, user is inactive'];

            } else {
                if($request->hosting == 'opossum' &&
					!empty($request->ONLY_ONE_HOST)) {

                    $request->session()->put('key', 'opossum');
                    $request->session()->put('ONLY_ONE_HOST', true);
                    $return = ["landing" => url('/onehost-opossum')];
                
				} else {
                    $return = ["landing" => url($this->landing_page)];
                }
            }

			if (!empty($terminal)) {
				//####### Threshold #######
				$tc = DB::table('terminalcount')->
					where('terminal_id', $terminal->id)->first();

				$allowed_receipt_count = $tc->allowed_receipt_count ?? 0;
				$receiptCount = DB::table('receipt')->
					where('terminal_id', $terminal->id)->
					get()->count();

				$tHoldPassed = DB::table('receipt')->
					where('terminal_id', $terminal->id)->
					limit($allowed_receipt_count)->
					orderBy('created_at', 'desc')->first();

					$date = new \DateTime(date('Y-m-d',
						strtotime($tHoldPassed->created_at ?? now())));

					$now = new \DateTime(date('Y-m-d'));

					Log::debug([
						"date" => $date,
						"now" => $now,
						"receiptCount" => $receiptCount,
						"allowed_receipt_count" => $allowed_receipt_count,
						"client_ip" => $client_ip,
						"server_ip" => $server_ip,
					]);
			
					if ($date < $now &&
						$receiptCount > $allowed_receipt_count &&
						$client_ip != $server_ip ) {

						Auth::logout();
						$return = ['login_error' =>
							'Unable to login, please contact your administrator'];
					}
				
			/*
				if (!empty($detectHardware)) {
					\Log::info(["detectHardware" => $detectHardware]);
					if ($terminal->hw_addr != $detectHardware[0]['MAC']) {
						Auth::logout();
						$return = ['login_error' =>
							'Unable to login, please contact your administrator'];
					}	
				}
			 */
			}

        } else {
            $return = ['login_error' => 'Invalid username and password'];
        }

		if (isset($return['landing']))
			$this->loginlogout('login');

        return $return;
    }


    public function logout(Request $request)
    {
		$this->loginlogout('logout');
        Auth::logout();

        Log::debug('Key'.$request->session()->get('key'));

		$session = $request->session()->get('key');
		$request->session()->forget('key');

        if($session == 'opossum') {
            return redirect('/onehost-opossum');
        }
        return redirect('/');
    }

	function loginlogout($type) {
		$location = DB::table('location')->first();

		if ($type == 'login') {
			
			$lastlogin = DB::table('loginout')->
				where('user_id', Auth::User()->id)->latest()->first();

			// Protect from empty loginout table
			if (!empty($lastlogin)) {
				DB::table('loginout')->where('user_id', Auth::User()->id)->
					whereNull('logout')->update([
					'logout'		=> 	date("Y-m-d 23:59:59",
										strtotime($lastlogin->login)),
					'updated_at'	=>	now()
				]);
			}

			DB::table('loginout')->insert([
				'login'			=> now(),
				'location_id'	=> $location->id,
				'user_id'		=> Auth::User()->id,
				'shift_id'		=> 0,
				'created_at'	=> now(),
				'updated_at'	=> now()
			]);

		} elseif ($type == 'logout') {
			DB::table('loginout')->where([
				'location_id'	=> $location->id,
				'user_id'		=> Auth::User()->id,
			])->whereNull('logout')->update([
				'logout'		=> now()
			]);
		}		
	}


	function log2laravel(Request $request) {
		$level = $request->level;	
		$string = $request->string;

		switch ($level) {
			case 'error':
				Log::error($string);
				break;
			case 'debug':
				Log::debug($string);
				break;
			case 'info':
			default:
				Log::info($string);
		}
	}


    public function postDateToOceania  (Request $request){
     
        $response = array('response' => 'Data inserted', 'success'=>true);

$validator = Validator::make($request->all(), [ 
        'id' => 'required|unique:oneway',
        'self_merchant_id'=> 'required',
        'company_name'   => 'required',
        'business_reg_no'=> 'required',
        'address'   => 'required',
        'contact_name'=> 'required',
        'mobile_no'   => 'required',
    ]);
    if ($validator->fails()) {
        $response['response'] = $validator->messages();
    }else{
       $id =   DB::table('oneway')->insertGetId([
                'id'=> $request->input('id'),
                'self_merchant_id'=> $request->input('self_merchant_id'),
                'company_name'   => $request->input('company_name'),
                'business_reg_no'=> $request->input('business_reg_no'),
                'address'   => $request->input('address'),
                'contact_name'=> $request->input('contact_name'),
                'mobile_no'   => $request->input('mobile_no'),
                'created_at'    => now(),
               'updated_at'    => now()
            ], 'id');
         DB::table('onewayrelation')->insert([
                'oneway_id'=> $id,
                'status'   => 'active',
                'ptype' =>'dealer', 
                'created_at'    => now(),
                'updated_at'    => now()
            ]);
          DB::table('onewaylocation')->insert([
                'oneway_id'=> $id,
                'location_id'=>$request->input('location_id'),
                'created_at'    => now(),
                'updated_at'    => now()
            ]);
           //Log::channel('Onewaylog')->info('Data inserted for the company: ' . $request->input('company_name'));


}

return $response;
        //Log::info($request);;

        
    }


    public function postDateToOceaniatwoway  (Request $request){
        //$response = "yes";
         $response = array('response' => 'Data inserted', 'success'=>true);

$validator = Validator::make($request->all(), [ 
        'id' => 'required|unique:merchantlink',
        'initiator_user_id'=> 'required',
        'responder_user_id'   => 'required',
        'selfMerchantId'=>'required',
    ]);
    if ($validator->fails()) {
        $response['response'] = $validator->messages();
    }else{
       $merchantlink_id =   DB::table('merchantlink')->insertGetId([
                'id'=>$request->input('id'),
                'responder_user_id'   => $request->input('responder_user_id'),
                'initiator_user_id'=> $request->input('initiator_user_id'),
                'created_at'    => now(),
               'updated_at'    => now()

            ], 'id');

      DB::table('company')->insert([
                'id'=>$request->input('company_id'),
                'name'=>$request->input('name'),
                'business_reg_no'=>$request->input('business_reg_no'), 
                'systemid'=>$request->input('systemid'),
                'corporate_logo'=>$request->input('corporate_logo'), 
                'owner_user_id'=>$request->input('owner_user_id'),
                'gst_vat_sst'=>$request->input('gst_vat_sst'),
                'currency_id'=>$request->input('currency_id'),
                'office_address'=>$request->input('office_address'), 
                'status'=>$request->input('status'), 
                'created_at'    => now(),
               'updated_at'    => now()

            ], 'id');

       $twoway_id =   DB::table('merchantlinkrelation')->insertGetId([
                'company_id'=> $request->input('company_id'),
                'merchantlink_id'   => $merchantlink_id,
                'default_location_id'=>$request->input('location_id'),
                'ptype'=>'dealer',
                'created_at'    => now(),
               'updated_at'    => now()

            ], 'id');

        $twoway_id =   DB::table('twoway')->insertGetId([
                'initiator_user_id'=> $request->input('initiator_user_id'),
                'responder_user_id'   => $request->input('responder_user_id'),
                'created_at'    => now(),
               'updated_at'    => now()
            ], 'id');

         $merchantrelation_id =   DB::table('merchantrelation')->insertGetId([
                'self_merchant_id'=> $request->input('selfMerchantId'),
                'twoway_id'   => $twoway_id,
                'partner_merchant_id'=> $request->input('responder_user_id'),
                'partner_oneway_id'=>0,
                'is_dealer'=>true,
                'default_location_id'=> $request->input('location_id'),
                'created_at'    => now(),
               'updated_at'    => now()
            ], 'id');

           ////Log::channel('Onewaylog')->info('Data inserted for the company: ' . $request->input('responder_user_id'));

}

return $response;
        //Log::info($request);;

        
    }

    
}
