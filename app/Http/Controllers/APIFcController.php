<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class APIFcController extends Controller
{

	protected $init_company 	= false;
	protected $dest_location 	= null;
	protected $src_location 	= null;
	protected $target_table		= [
		'controller'			=> 	'local_controller',
		'pump'		 			=> 	'local_pump',
		'nozzle'	 			=>	'local_pumpnozzle',
		'pts_baudrate' 			=>	'local_pts2_baudrate',
		'pts_protocol'			=>	'local_pts2_protocol',
		'probe_protocol'		=>	'local_probe_protocol',
		'product'				=>	'product',
		'prd_ogfuel'			=>	'prd_ogfuel',
		'og_localfuelprice' 	=>	'og_localfuelprice',
		"localprice"			=>	'localprice',
		'prd_category'			=>	'prd_category',
		'prd_subcategory'		=>	'prd_subcategory',
		'prd_brand'				=>	'prd_brand',
		'prd_inventory'			=>	'prd_inventory',
		'productbmatrixbarcode' => 	'productbmatrixbarcode',
		'productbarcode'		=>	'productbarcode',
		'prdbmatrixbarcodegen' 	=>	'prdbmatrixbarcodegen'
	];

	protected $api_except_route = ['localaccess.interface.pushdata','localaccess.interface.licence'];

	/*
	 * Json Structure (Request):
	 * [
	 * 		[
	 * 			{
	 * 				controller: {..}, 
	 * 				pump: [ 
	 *		 			{
	 *		 			pump: {..}, 
	 *		 			nozzle: [
	 *		 				{..}, 
	 *		 				{..}
	 *		 			]
	 *		 			}
	 * 				]
	 * 			}
	 * 		],
	 * 		[...]
	 * 	]
	 */

	public function __construct(){
		try {
		
			$routeName = request()->route()->getName();
		
			if (!in_array($routeName, $this->api_except_route)) {		
				if (empty(request()->header('X-API-KEY-FC')))
					throw new \Exception("Security key not found");

				if (!$this->auth_key(request()->header('X-API-KEY-FC')))
					throw new \Exception("Invalid security key");
			} else {
				$this->init_company 	= DB::table('company')->first();
				$this->dest_location	= DB::table('location')->first();
			}

		} catch (\Exception $e) {
			$this->handleError($e);
		}
	}

	/*
	 *
	 * Misc Functions
	 *
	*/

	public function push_fc(Request $request) {
		try {
			\Log::info([
				"Push Request"	=> $request->ip()
			]);
			
	/*		$validation = Validator::make($request->all(), [
				"packets"	=>	"required",
				'baudrate'	=>	'required',
				'protocol'	=>	'required'
			]);
	 */
			//if ($validation->fails())
				//throw new \Exception("packet data is missing", 400);

			$packets				= json_decode($request->packets ?? '[]', true);
			$baudrate 				= json_decode($request->baudrate ?? '[]', true);
			$pts_protocol 			= json_decode($request->pts_protocol ?? '[]', true);
			$probe_protocol 		= json_decode($request->probe_protocol ?? '[]', true);
			$products		 		= json_decode($request->products ?? '[]', true);
			$thumbnailData			= json_decode($request->thumbnailData ?? '[]', true);
			$og_localfuelprice		= json_decode($request->og_localfuelprice ?? '[]', true);
			$locationPrice			= json_decode($request->locationPrice ?? '[]', true);
			$prdInventory			= json_decode($request->prdInventory ?? '[]', true);
			$productbmatrixbarcode	= json_decode($request->productbmatrixbarcode ?? '[]', true);
			$productbarcode 		= json_decode($request->productbarcode ?? '[]', true);
			$prdbmatrixbarcodegen	= json_decode($request->prdbmatrixbarcodegen ?? '[]', true);

			
			\Log::info([
				"PRICE"	=>	$og_localfuelprice,
				"products"	=> $products,
				"locationPrice" =>$locationPrice
			]);
				
			if (count($baudrate) > 0) {
				foreach ($baudrate as $BR) {
					$this->insertBaudrate($BR);
				}
			}

			if (count($pts_protocol) > 0) {
				foreach ($pts_protocol as $prt) {
					$this->insertPTSProtocol($prt);
				}
			}
		
			if (count($probe_protocol) > 0) {
				foreach ($probe_protocol as $prt) {
					$this->insertPROBEProtocol($prt);
				}
			}

			if (count($products) > 0) {
				foreach ($products as $p) {
					$this->insertProduct($p);
				}
			}
			
			if (count($thumbnailData) > 0) {
				foreach ($thumbnailData as $thumbnail) {
					$this->insertThumbnal($thumbnail);
				}
			}

			if (count($og_localfuelprice) > 0) {
				foreach ($og_localfuelprice as $lfp) {
					$this->insertLocalFuelPrice($lfp);
				}
			}

			if (count($packets) > 0) {
				foreach($packets as $packet) {
					$this->insertController($packet);
				}
			}


			if (count($locationPrice) > 0) {
				foreach ($locationPrice as $lp) {
					$this->insertLocationPrice($lp);
				}
			}

			
			if (count($prdInventory ) > 0) {
				foreach ($prdInventory as $pI) {
					$this->insertPrdInventory($pI);
				}
			}

			
			if (count($productbmatrixbarcode) > 0) {
				foreach ($productbmatrixbarcode as $pmb) {
					app('App\Http\Controllers\APIFcController')->insertPMB($pmb);
				}
			}

			
			if (count($productbarcode) > 0) {
				foreach ($productbarcode as $pbc) {
					app('App\Http\Controllers\APIFcController')->insertProductbarcode($pbc);
				}
			}
			
			if (count($prdbmatrixbarcodegen) > 0) {
				foreach ($prdbmatrixbarcodegen as $pm) {
					app('App\Http\Controllers\APIFcController')->insertPrdMarix($pbc);
				}
			}

			return json_encode($this->init_company);
		} catch (\Exception $e) {
			$this->handleError($e, $e->getCode());
		}
	}

	/*
	 * Parsing function
	*/

	public function insertThumbnal($thumbnail) {
		try {
			\Log::info($thumbnail);
			$this->check_location($thumbnail['folderAddr']);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $thumbnail['httpAddr']);
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_AUTOREFERER, false);
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // <-- don't forget this
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // <-- and this
			$result = curl_exec($ch);
			curl_close($ch);
			$fp = fopen(public_path().$thumbnail['fileAddr'], 'wb');
			fwrite($fp, $result);
			fclose($fp);
		 
		} catch (\Exception $e) {
			\Log::info("File Save error (APIFcControler) :". $e->getMessage());
			//$this->handleError($e, $e->getCode());
		}
	}


	public function insertPrdCategory($prd_cat) {
		try {
			$cat_condition = [
				'name' => $prd_cat['name']
			];
			
			$this->updateOrInsert('prd_category',
			   		$cat_condition, $prd_cat);
	
		} catch (\Exception $e) {
			$this->handleError($e, $e->getCode());
		}
	}

	public function insertPrdInventory($rec) {
		try {
			$product_id = DB::table('product')->
				where('systemid', $rec['systemid'])->
				first()->id ?? 0;
			
			unset($rec['systemid']);
			$rec['product_id']  = $product_id;

			$prd_inv_condition = [
				'product_id' => $product_id
			];
			
			$this->updateOrInsert('prd_inventory',
			   		$prd_inv_condition, $rec);
		} catch (\Exception $e) {
			$this->handleError($e, $e->getCode());
		}
	}

	public function insertPrdSubCategory($prd_sub_cat) {
		try {
	
			$cat_id = DB::table('prd_category')->
				where('name', $prd_sub_cat['cat_name'])->
				first()->id ?? 0;

			$sub_cat = [
				'category_id' => $cat_id,
				'name'		 => $prd_sub_cat['name']
			];

			$prd_sub_cat['category_id'] = $cat_id;
			unset($prd_sub_cat['cat_name']);
			
			$this->updateOrInsert('prd_subcategory',
			   		$sub_cat, $prd_sub_cat);

		} catch (\Exception $e) {
			$this->handleError($e, $e->getCode());
		}
	}

	public function insertPrdBrand($brand) {
		try {
			$brand_condition = [
				'name' => $brand['name']
			];
	
			$this->updateOrInsert('prd_brand',
			   		$brand_condition, $brand);
		} catch (\Exception $e) {
			$this->handleError($e, $e->getCode());
		}

	}

	public function insertPMB($pmb) {
	
		try {
			//productbmatrixbarcode		
			$product_rec = DB::table('product')->
				where('systemid', $pmb['systemid'])->
				first();

			unset($pmb['systemid']);
			$conditions = [
				'product_id'	=> $product_rec->id
			];
		
			$pmb['product_id'] =  $product_rec->id;

			$this->updateOrInsert('productbmatrixbarcode',
			   		$conditions, $pmb);
		} catch (\Exception $e) {
			$this->handleError($e, $e->getCode());
		}
	}

	
	public function insertProductbarcode($bc) {
	
		try {
			$product_rec = DB::table('product')->
				where('systemid', $bc['systemid'])->
				first();

			unset($bc['systemid']);
			$conditions = [
				'product_id'	=> $product_rec->id
			];
		
			$bc['product_id'] =  $product_rec->id;

			$this->updateOrInsert('productbarcode',
			   		$conditions, $bc);

		} catch (\Exception $e) {
			$this->handleError($e, $e->getCode());
		}
	}

	public function insertPrdMarix($bc) {
	
		try {
			$product_rec = DB::table('product')->
				where('systemid', $bc['systemid'])->
				first();

			unset($bc['systemid']);
			$conditions = [
				'product_id'	=> $product_rec->id
			];
		
			$bc['product_id'] =  $product_rec->id;

			$this->updateOrInsert('prdbmatrixbarcodegen',
			   		$conditions, $bc);

		} catch (\Exception $e) {
			$this->handleError($e, $e->getCode());
		}
	}

	public function insertLocationPrice($lp) {
		try {

			$product_rec = DB::table('product')->
				where('systemid', $lp['systemid'])->
				first();

			$is_exist = DB::table('localprice')->
				where('product_id', $lp['product_id'])->
				first();

			if (empty($product_rec))
				return;

			unset($lp['systemid']);
			unset($lp['franchise_id']);
			
			if (!empty($is_exist))
				unset($lp['recommended_price']);

			$lp['product_id'] = $product_rec->id;

			$locationPriceCondition = [
				"product_id"	=>	$lp['product_id'],
				"upper_price"	=>	$lp['upper_price'],
				"lower_price"	=>	$lp['lower_price'],
			];	

			
			\Log::info(['cond' => $lp ]);
			
			$this->updateOrInsert('localprice',
			   		$locationPriceCondition, $lp);

		} catch (\Exception $e) {
			$this->handleError($e, $e->getCode());
		}
	}

	public function insertLocalFuelPrice($fuelPriceData) {
		try {
			$og_fuel_id = DB::table('prd_ogfuel')->
				join('product','prd_ogfuel.product_id','product.id')->
				where('product.systemid', $fuelPriceData['systemid'])->
				select('prd_ogfuel.*')->
				first()->id;

			$location	= DB::table('location')->first();
			$company	= DB::table('company')->first();

			$user_id =  DB::table('users')->
				where('systemid', $fuelPriceData['user_systemid'])->
				first()->id ?? $company->owner_user_id;

			unset($fuelPriceData['systemid']);
			unset($fuelPriceData['user_systemid']);

			$fuelPriceData['ogfuel_id']		= $og_fuel_id;
			$fuelPriceData['location_id']	= $location->id;
			$fuelPriceData['company_id']	= $company->id;
			$fuelPriceData['user_id']		= $user_id;

			$fuelPriceCondition = [
				'ogfuel_id'	=>	$og_fuel_id,
			];

			$this->updateOrInsert('og_localfuelprice',
			   		$fuelPriceCondition, $fuelPriceData);

		} catch (\Exception $e) {
			$this->handleError($e, $e->getCode());
		}
	}
	
	public function insertProduct($productData) {
		try {

			$productCondition = [
				"systemid" =>	$productData['systemid']
			];

			$new_product_id = $this->updateOrInsert('product', $productCondition, $productData);

			if ($productData['ptype'] != 'oilgas')
				return;

			$prd_ogfuelData = [
				"product_id" => $new_product_id,
				"created_at" =>	$productData['created_at'],
				"updated_at" =>	$productData['updated_at']
			];

			$prd_ogfuelCondition = [
				"product_id" => $new_product_id
			];

			$this->updateOrInsert('prd_ogfuel', $prd_ogfuelCondition, $prd_ogfuelData);

		} catch (\Exception $e) {
			$this->handleError($e, $e->getCode());
		}
	}

	public function insertPROBEProtocol($protocolData) {
		try {
			unset($protocolData['id']);
			$protocolCondition = [
				"protocol_no"	=> $protocolData['protocol_no'],
				'protocol_name'	=> $protocolData['protocol_name']
			];

			$this->updateOrInsert('probe_protocol', $protocolCondition, $protocolData);

		} catch (\Exception $e) {
			$this->handleError($e, $e->getCode());
		}
	}	

	public function insertPTSProtocol($protocolData) {
		try {
			unset($protocolData['id']);
			$protocolCondition = [
				"protocol_no"	=> $protocolData['protocol_no'],
				'protocol_name'	=> $protocolData['protocol_name']
			];

			$this->updateOrInsert('pts_protocol', $protocolCondition, $protocolData);

		} catch (\Exception $e) {
			$this->handleError($e, $e->getCode());
		}
	}	

	public function insertBaudrate($baudrateData) {
		try {
			unset($baudrateData['id']);
			$baudrateCondition = [
				"index"		=> $baudrateData['index'],
				'baudrate'	=> $baudrateData['baudrate']
			];

			$this->updateOrInsert('pts_baudrate', $baudrateCondition, $baudrateData);

		} catch (\Exception $e) {
			$this->handleError($e, $e->getCode());
		}
	}	

	public function insertController($packet) {
		try {
			$controllerData = $packet['controller'];
			$pumpData 		= $packet['pump'];
			
			$controllerData['company_id']	= $this->init_company->id;
			$controllerData['location_id']	= $this->dest_location->id;
			unset($controllerData['id']);
			unset($controllerData['fw_rel_date']);
			$controllerCondition = [
				"systemid"		=>	$controllerData['systemid'],
				"location_id"	=>	$controllerData['location_id'],
				"company_id"	=>	$controllerData['company_id']
			];
			
			$controllerId = $this->updateOrInsert('controller', $controllerCondition, $controllerData);
			
			if (count($pumpData) > 0) {
				foreach ($pumpData as $pd) {
					$this->insertPump($pd, $controllerId);	
				}
			}

		} catch (\Exception $e) {
			$this->handleError($e, $e->getCode());
		}
	}

	private function insertPump($myPump, $controllerId) {
		try {
			$pumpData	= $myPump['pump'];
			$nozzleData	= $myPump['nozzle'];

			$pumpData['controller_id'] = $controllerId;
			$pumpData['pts2_protocol_id'] = $pumpData['og_pts2_protocol_id'];

			unset($pumpData['og_pts2_protocol_id']);
			unset($pumpData['id']);

			$pumpCondition = [
				"systemid"		=>	$pumpData['systemid'],
				"controller_id"	=>	$controllerId
			];
		
			$pumpId = $this->updateOrInsert('pump', $pumpCondition, $pumpData);
			if (count($nozzleData) > 0) {
				foreach ($nozzleData as $nozzle) {
					$this->insertNozzle($nozzle, $pumpId);	
				}
			}

		} catch (\Exception $e) {
			$this->handleError($e, $e->getCode());
		}
	}

	private function insertNozzle($nozzleData, $pumpId) {
		try {
			$nozzleData['pump_id'] = $pumpId;
			
			\Log::info([
				'p_systemid'	=>		$nozzleData['p_systemid']
			]);

			$og_fuel_id = DB::table('prd_ogfuel')->
				join('product','prd_ogfuel.product_id','product.id')->
				where('product.systemid', $nozzleData['p_systemid'])->
				select('prd_ogfuel.*')->
				first()->id ?? null;
		
			if (empty($og_fuel_id))
				return;

			unset($nozzleData['id']);
			unset($nozzleData['p_systemid']);

			$nozzleData['ogfuel_id'] = $og_fuel_id;

			$nozzleCondition = [
				"nozzle_no"	=>	$nozzleData['nozzle_no'],
				'pump_id'	=>	$pumpId
			];

			$this->updateOrInsert('nozzle', $nozzleCondition, $nozzleData);

		} catch (\Exception $e) {
			$this->handleError($e, $e->getCode());
		}
	}

	private function updateOrInsert($tableName, $targetCondition, $targetData) {
		try {
			$targetTable = $this->getTargetTable($tableName);
			if (isset($targetData['id']))
				unset($targetData['id']);	

			$shouldInsert = $targetTable->
				where($targetCondition)->
				first();

			if (empty($shouldInsert)) {
				$targetId = $targetTable->insertGetId($targetData);	
				
			} else {
				$targetCondition['updated_at']  = $targetData['updated_at']; 

				$shouldUpdate = $targetTable->
					where($targetCondition)->
					first();
					
				unset($targetCondition['updated_at']);

				if (empty($shouldUpdate)) {

					$targetTable = $this->getTargetTable($tableName);
					$targetTable->where($targetCondition)->update($targetData);
					
				}

				$targetId = $shouldInsert->id;
				
			}
			return $targetId;	
		}	catch (\Exception $e) {
			$this->handleError($e, $e->getCode());
		}
	}

	private function auth_key($key) {
		$is_valid_key = DB::table('fc_apisecurity')->
			where('app_key', $key)->
			first();
		
		if (!empty($is_valid_key)) {
			$this->init_company = DB::table('company')->
				where('systemid', $is_valid_key->company_systemid)->
				first();

			$this->dest_location = DB::table('location')->
				where('systemid',$is_valid_key->dest_location_systemid)->
				first();

			$this->src_location	= DB::table('location')->
				where('systemid',$is_valid_key->source_location_systemid)->
				first();

			$return = true;
		} else {
			$return = false;
		}
		return $return;
	}
	

	/*
	 *
	 * Misc Functions
	 *
	*/
	private function getTargetTable($name) {
		return	DB::table($this->target_table[$name]);
	}

	private function getSrcTable($name) {
		return	DB::table($this->src_table[$name]);
	}



	private function handleError(\Exception $e, $error_code = 403) {
		\Log::info([
				"Error"	=> 	$e->getMessage(),
				"File"	=>	$e->getFile(),
				"Line"	=>	$e->getLine()
			]);
		abort(response()->json(
			['message' => $e->getMessage()], 404)); 
	}

	public function check_location($location){
        $location = array_filter(explode('/', $location));
        $path = public_path();

        foreach ($location as $key) {
            $path .= "/$key";

            if (is_dir($path) != true) {
                mkdir($path, 0775, true);
            }
        }
    }
}
