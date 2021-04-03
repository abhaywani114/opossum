<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use \Log;
use \App\Http\Controllers\OposPetrolStationPumpController as petrolStation;
use \App\Http\Controllers\LocalFuelPriceController;

class LocalFuelController extends Controller
{

    function fuelLocalPriceDatatable(Request $request)
    {
        try {

            $company = DB::table('company')->first();
            $location = DB::table('location')->first();
            $pump = DB::table('local_pump')->
            join('local_controller', 'local_controller.id', '=', 'local_pump.controller_id')->
            where([
                "local_controller.location_id" => $location->id,
                "local_controller.company_id" => $company->id
            ])->
            select("local_pump.*")->
            get();

            return Datatables::of($pump)->
            addIndexColumn()->
            addColumn('pump_no', function ($data) {
                return <<<EOD
					<span class="os-linkcolor" style='cursor:pointer;'
					onclick="openDetailModal($data->id)">$data->pump_no</span>
EOD;
            })->
            escapeColumns([])->
            make(true);
        } catch (\Exception $e) {
            \Log::info([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            abort(404);
        }
    }

    function fuelLocalPriceModal(Request $request)
    {
        try {

            $pump = DB::table('local_pump')->
            where('local_pump.id', $request->pump_id)->
            first();

            $local_pts2_protocol = DB::table('local_pts2_protocol')->
            where('protocol_no', $pump->pts2_protocol_id)->
            first();

            $baud = DB::table('local_pts2_baudrate')->
            where('index', $pump->baudrate)->
            first();

            $nozzleData = DB::table('local_pumpnozzle')->
            join('prd_ogfuel', 'prd_ogfuel.id',
                '=', 'local_pumpnozzle.ogfuel_id')->
            leftjoin('product', 'product.id', '=', 'prd_ogfuel.product_id')->
            where('local_pumpnozzle.pump_id', $pump->id)->
            select('local_pumpnozzle.nozzle_no',
                'local_pumpnozzle.ogfuel_id', 'product.name as fuel_name')->
            get();

            $nozzleData->map(function ($f) {
                $f->price = number_format(
                    $this->getPrice($f->ogfuel_id, request()->location_id), 2);
            });

            return view('local_fuelprice.local_pumpconfig_modal', compact('pump',
                'local_pts2_protocol', 'baud', 'nozzleData'));

        } catch (\Exception $e) {
            \Log::info([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            abort(404);
        }
    }

    function showFuelLocalPriceDatatable(Request $request)
    {

        $fuelRecord = DB::table('product')->
        leftjoin('prd_ogfuel', 'product.id', 'prd_ogfuel.product_id')->
        leftjoin('og_localfuelprice', 'og_localfuelprice.ogfuel_id', 'prd_ogfuel.id')->
        where('product.ptype', 'oilgas')->
        orderBy('og_localfuelprice.start', 'desc')->
        select("product.*", 'og_localfuelprice.ogfuel_id',
            'og_localfuelprice.price','og_localfuelprice.controller_price', 'prd_ogfuel.id as alt_id', 'og_localfuelprice.user_id', 'og_localfuelprice.updated_at as u_date',
            'og_localfuelprice.id as localfuelprice_id'
        )->
        get();


        return Datatables::of($fuelRecord)->
        addIndexColumn()->
        addColumn('product_systemid', function ($data) {
            return $data->systemid;
        })->
        addColumn('product_name', function ($data) {
            $img_src = '/images/product/' .
                $data->systemid . '/thumb/' .
                $data->thumbnail_1;

            $img = "<img src='$img_src' data-field='inven_pro_name' style=' width: 25px;
				height: 25px;display: inline-block;margin-right: 8px;object-fit:contain;'>";

            return $img . $data->name;
        })->
        addColumn('controller_price', function ($data) {
            return  number_format(($data->controller_price ?? 0) / 100, 2);
        })->
        addColumn('price', function ($data) {
            $price = number_format(($data->price ?? 0) / 100, 2);
            $fk_id = $data->ogfuel_id;
            if(empty($fk_id)){
                $fk_id =  $data->alt_id;
            }


            /*		$is_current = DB::table('og_localfuelprice')->
                        where([
                            'ogfuel_id' 	=> $fk_id,
                        ])->
                        whereDate('start' , '<=',\Carbon\Carbon::today())->
                        first();
             */

            //$fk_id = $data->localfuelprice_id;
            $html = <<<EOD
				<span class="os-linkcolor" onclick="price_set_modal('$price', '$fk_id')" style="cursor:pointer">
					$price</span>
EOD;

            //		$isToday = \Carbon\Carbon::parse(date('Y-m-d',strtotime($data->start)));
            //	return ($isToday->isToday() || $isToday->isFuture()) ? $html:$price;
            return $html;
        })->
        addColumn('user', function ($data) {

            return DB::table('users')->find($data->user_id)->fullname ?? '';
        })->
        addColumn('user_date', function ($data) {
            return !empty($data->u_date) ?
                date("dMy H:i:s", strtotime($data->u_date)) : '';
        })->
        escapeColumns([])->
        make(true);
    }

    function showFuelLocalPriceUpdate(Request $request)
    {
        try {
            $company = DB::table('company')->first();
            $location = DB::table('location')->first();
            $validation = Validator::make($request->all(), [
                "field" => "required",
                "value" => "required",
                "id" => "required"
            ]);

            Log::debug('***** showFuelLocalPriceUpdate *****');
            Log::debug('all()=' . json_encode($request->all()));
            Log::debug('validation=' . json_encode($validation->fails()));

            if ($validation->fails()) {
                $message = $validation->errors();

				Log::debug('message=' . json_encode($message));

                return response()->json(compact('message'));
            }

            $is_exist = DB::table('og_localfuelprice')->
				where([
					"id" => $request->id,
				])->first();

            Log::debug('is_exist=' . json_encode($is_exist));

            $array = [];
            $array['updated_at'] = date('Y-m-d H:i:s');
            $array["company_id"] = $company->id;
            $array["location_id"] = $location->id;

            switch ($request->field) {
                case 'start':
                    $array['start'] = date("Y-m-d 00:00:00",
						strtotime($request->value));
                    break;
                case 'price':
                    $array['user_id'] = \Auth::User()->id;
                    $array['price'] = $request->value;
                    break;
                default:
                    throw new \Exception("Invalid data type");
                    break;
            }

            if (!empty($is_exist)) {
                DB::table('og_localfuelprice')->
                where([
                    "ogfuel_id" => $request->id,
                ])->update($array);
            } else {
                $array['created_at'] = date('Y-m-d H:i:s');
                $array['start'] = date('Y-m-d H:i:s');
                $array["ogfuel_id"] = $request->id;
                $array['user_id'] = \Auth::User()->id;
                DB::table('og_localfuelprice')->insert($array);
            }

            return response()->json(["status" => true]);

        } catch (\Exception $e) {
            \Log::info([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            abort(404);
        }
    }

    public function getOgFuelQualifiedProducts($company_id = null)
    {

        $products_chunck = array();
        $filter = array();

        $ids = DB::table('prd_ogfuel')->get()->
        pluck('product_id');

        $products = DB::table('product')->
        select('product.*', 'prd_ogfuel.id as og_f_id')->
        join('prd_ogfuel', 'product.id', 'prd_ogfuel.product_id')->
        whereIn('product.id', $ids)->
        where([
            ['product.name', '<>', null],
            ['product.photo_1', '!=', null],
            ['product.ptype', 'oilgas']
        ])->
        get();

        return $products;
    }

    public function localFuelPricePushHardware(Request $request)
    {
        try {
            $controller = $this->getControllerIPs();

            $fuelgradesConfig = $this->fuelgradesConfig();
            $mypetrolStation = new petrolStation();



            foreach ($controller as $c) {
                $pumpConfig = $this->pumpConfig($c->id);
                $nozzleConfig = $this->nozzleConfig($c->id, collect($fuelgradesConfig));

                $hardware_request = new Request();
                $hardware_request->ipaddr = $c->ipaddress;

                $hardware_request->ports = $pumpConfig['Protocol'];
                $hardware_request->pumps = $pumpConfig['Port'];

                //	$mypetrolStation->setPumpsConfiguration($hardware_request);

                $hardware_request->fuelgrades = array_map(function ($f) {
                    unset($f['og_f_id']);
                    return $f;
                }, $fuelgradesConfig);
                $mypetrolStation->setFuelGradesConfiguration($hardware_request);
                $save = new LocalFuelPriceController;
				\Log::info(['fuelgradesConfig' => $fuelgradesConfig]);
                $save->updateControllerPrice($fuelgradesConfig);

                //	$hardware_request->pumpnozzles = $nozzleConfig;
                //	$mypetrolStation->setPumpNozzlesConfiguration($hardware_request);
            }
            /*
            $save = new LocalFuelPriceController;
                $save->updateControllerPrice($fuelgradesConfig);
            */
            return ["status" => true];
        } catch (\Exception $e) {

            \Log::info([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);

            abort(404);
        }
    }

    //////////////////////////////////////
    //	Helper	Function

    function getPrice($ogfuel_id, $location_id = null)
    {

        $localPrice = DB::table('og_localfuelprice')->
        where([
            'ogfuel_id' => $ogfuel_id,
        ])->
        whereDate('start', '<=', \Carbon\Carbon::today())->
        first();

        if (!empty($localPrice))
            return $localPrice->price / 100 ?? 0;
        else
            return 0;
    }
	
	function getControllerPrice($ogfuel_id, $location_id = null)
    {

        $localPrice = DB::table('og_localfuelprice')->
        where([
            'ogfuel_id' => $ogfuel_id,
        ])->
        whereDate('start', '<=', \Carbon\Carbon::today())->
        first();

        if (!empty($localPrice))
            return $localPrice->controller_price / 100 ?? 0;
        else
            return 0;
    }

    private function getControllerIPs()
    {
        $ipAddrs = DB::table('local_controller')->
        select("id", "ipaddress")->
        get();
        return $ipAddrs;
    }

    private function pumpConfig($controller_id)
    {
        try {

            \Log::info("**********8pumpConfig()***************");
            $pump_details = DB::table('local_pump')->
            where('local_pump.controller_id', $controller_id)->
            select('local_pump.id', 'local_pump.pump_no', 'local_pump.pump_port',
                'local_pump.comm_address', 'local_pump.pts2_protocol_id',
                'local_pump.baudrate', "local_pump.comm_address"
            )->
            get();

            \Log::info([
                "pump_details" => !empty($pump_details)
            ]);

            $protocol = [];
            $port = [];
            $index = 1;
            foreach ($pump_details as $pump) {
                $protocol[] = [
                    "Id" => $index,
                    "Protocol" => $pump->pts2_protocol_id,
                    "BaudRate" => $pump->baudrate
                ];

                $port[] = [
                    "Id" => $index,
                    "Port" => $pump->pump_port,
                    "Address" => $pump->comm_address
                ];
                $index++;
            }

            return ["Protocol" => $protocol, "Port" => $port];

        } catch (\Exception $e) {
            Log::info([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            abort(404);
        }
    }


    public function fuelgradesConfig()
    {
        try {
            Log::info("********* fuelgradesConfig()***********");
            $fuelData = $this->getOgFuelQualifiedProducts();

            $fuelgradesConfig = [];
            $formatted_fuelgradesConfig = [];
            $index = 1;

            foreach ($fuelData as $fuel) {
                $fuelgradesConfig[] = [
                    "Name" => $fuel->name,
                    "Price" => $this->getPrice($fuel->og_f_id),
					"og_f_id" => $fuel->og_f_id,
					'systemid' => $fuel->systemid
                ];
            }
		
			//fixing order
			uasort($fuelgradesConfig, function($a,$b){
				return strcmp($a['systemid'], $b['systemid']);
			});

            foreach ($fuelgradesConfig as $fuel) {
				unset($fuel['systemid']);
				$fuel['Id'] = $index;
				$formatted_fuelgradesConfig[] = $fuel;
				$index++;
			}

            return $formatted_fuelgradesConfig;
        } catch (\Exception $e) {
            Log::info([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            abort(404);
        }
    }

    public function nozzleConfig($controller_id, $fuelConfig)
    {
        try {
            \Log::info("*********nozzleConfig()*************");
            $nozzleDetails = DB::table('local_pumpnozzle')->get()->groupBy('pump_id');

            \Log::info([
                "nozzleDetails" => !empty($nozzleDetails)
            ]);

            $nozzleConfig = [];
            $pumpIndex = 1;
            foreach ($nozzleDetails as $byPump) {

                $fuelGradeIds = [];
                $fuelGradeIdsFormated = [];
                foreach ($byPump as $nozzle) {

                    $nozleFuelIndex = $fuelConfig->where('og_f_id', $nozzle->ogfuel_id)[0]['Id'] ?? false;
                    if ($nozleFuelIndex)
                        $fuelGradeIds[$nozzle->nozzle_no] = $nozleFuelIndex;

                    \Log::info([
                        "nozleFuelIndex" => !empty($nozleFuelIndex)
                    ]);
                }

                for ($i = 1; $i <= 6; $i++)
                    $fuelGradeIdsFormated[] = $fuelGradeIds[$i] ?? 0;

                $nozzleConfig[] = ["PumpId" => $pumpIndex, "FuelGradeIds" => array_values($fuelGradeIdsFormated)];
                $pumpIndex++;
            }

            return $nozzleConfig;
        } catch (\Exception $e) {
            Log::info([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            abort(404);
        }
    }
}
