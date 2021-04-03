<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpClient\HttpClient;

class SetupController extends Controller
{

    public function licenceInterfaceActivate(Request $request)
    {
        try {
            // From URL to get webpage contents.
            $url = env('MOTHERSHIP_URL') . '/localaccess/interface/licence-activate';
			$HW_Addr = $this->getMacLinux();

            Log::debug("url=" . $url);

            $post = $request->all();
            $post['LOCAL_IPADDR']	= env('LOCAL_IPADDR');
			$post['tsystem']		= env('TSYSTEM');
			$post['HW_Addr']		= $HW_Addr;
			$post['api_key']		= env('APP_KEY');

            Log::debug('post=' . json_encode($post));

            $cURLConnection = curl_init($url);
            curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $post);
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYHOST, false);
            $apiResponse = curl_exec($cURLConnection);
            curl_close($cURLConnection);
            $data = json_decode($apiResponse, true);
            if (isset($data['error'])) {
                return $data;
            }

            Log::debug('data=' . json_encode($data));
            Log::info([
                "Server reponse" => json_encode($data)
            ]);

            //userdetails
            $users = $data['users'];

            foreach ($users as $user_data) {
                $user_data = [
                    'id' => $user_data['id'],
                    "systemid" => $user_data['systemid'],
                    "email" => $user_data['email'],
                    'fullname' => $user_data['name'],
                    'username' => $user_data['systemid'],
                    'password' => $user_data['password'],
                    'access_code' => 12345, // Warning: Hardcode
                    'status' => $user_data['status'],
                    'created_at' => $user_data['created_at'],
                    'updated_at' => $user_data['updated_at']
                ];

                $user_condition = [
                    "systemid" => $user_data['systemid']
                ];

                $new_user_id = $this->updateOrInsert('users', $user_condition, $user_data);
            }


			//insert company details
            $company_data = $data['company'];

            $company_data = [
                'systemid' => $company_data['systemid'],
                'name' => $company_data['name'],
                'business_reg_no' => $company_data['business_reg_no'],
                'corporate_logo' => $company_data['corporate_logo'],
                'gst_vat_sst' => $company_data['gst_vat_sst'],
                'office_address' => $company_data['office_address'],
                'owner_user_id' => $new_user_id,
                'status' => $company_data['status'],
                'approved_at' => $company_data['approved_at'],
                'created_at' => $company_data['created_at'],
                'updated_at' => $company_data['updated_at']
            ];

            $company_condition = [
                "systemid" => $company_data['systemid']
            ];

            $new_company_id = $this->updateOrInsert('company', $company_condition, $company_data);

            //insert location data
            $location_data = $data['location'];

            $location_data = [
                'systemid' => $location_data['systemid'],
                'name' => $location_data['branch'] ?? '',
                'address_line1' => $location_data['address_line1'],
                'address_line2' => $location_data['address_line2'],
                'address_line3' => $location_data['address_line3'],
                'start_work' => $location_data['start_work'],
                'close_work' => $location_data['close_work'],
                'created_at' => $location_data['created_at'],
                'updated_at' => $location_data['updated_at']
            ];

            $location_condition = [
                'systemid' => $location_data['systemid']
            ];

            $new_location_id = $this->updateOrInsert('location', $location_condition, $location_data);

            //insert terminal
            $terminal = $data['terminal'];
            foreach ($terminal as $terminal_data) {

                $terminal_data = [
                    'systemid' => $terminal_data['systemid'],
                    'ip_addr' => $terminal_data['ip_addr'],
                    'mode' => $terminal_data['mode'],
                    'status' => $terminal_data['status'],
                    'taxtype' => $terminal_data['taxtype'],
                    'tax_percent' => $terminal_data['tax_percent'],
                    'servicecharge' => $terminal_data['servicecharge'],
                    'local_logo' => $terminal_data['local_logo'],
                    'ip_addr' => env('OCEANIA_IPADDR'),
                    'created_at' => $terminal_data['created_at'],
                    'updated_at' => $terminal_data['updated_at']
                ];

                $terminal_condition = [
                    'systemid' => $terminal_data['systemid'],
                ];

                $new_terminal_id = $this->updateOrInsert('terminal', $terminal_condition, $terminal_data);
            }

            //insert vehicle data
            $vehicle_data = $data['vehicle_data'];
            foreach ($vehicle_data as $vehicle) {

                $vehicle = [
                    'systemid' => $vehicle['systemid'],
                    'merchant_id' => $vehicle['merchant_id'],
                    'vehicle_license' => $vehicle['vehicle_license'],
                    'status' => $vehicle['status'],
                    'created_at' => $vehicle['created_at'],
                    'updated_at' => $vehicle['updated_at']
                ];
                $vehicle_condition = [
                    'systemid' => $vehicle['systemid'],
                ];

                $new_vehicle_id = $this->updateOrInsert('lg_vehiclemgmt', $vehicle_condition, $vehicle);
            }

            

            ////////////////////////////////////////////////////

            $lic_locationkey = $data['lic_locationkey'];
            foreach ($lic_locationkey as $loc) {
                $lic_loc_condition = [
                    "license_key" => $loc['license_key'],
                    'company_id' => $new_company_id,
                    'location_id' => $new_location_id
                ];

                $loc['company_id'] = $new_company_id;
                $loc['location_id'] = $new_location_id;

                $this->updateOrInsert('lic_locationkey',
                    $lic_loc_condition, $loc);
            }

            $lic_terminalkey = $data['lic_terminalkey'];
            foreach ($lic_terminalkey as $terminal_key) {

                $terminal_id = DB::table('terminal')->
                where('systemid', $terminal_key['systemid'])->
                first()->id;

                $lic_terminal_condition = [
                    'terminal_id' => $terminal_id,
                ];

                unset($terminal_key['systemid']);
                $terminal_key['terminal_id'] = $terminal_id;
                $this->updateOrInsert('lic_terminalkey',
                    $lic_terminal_condition, $terminal_key);
            }

            $lic_key = $request->licensekey;
            DB::table('lic_locationkey')->
            where('license_key', $lic_key)->update([
                "has_setup" => 1
            ]);

        	$server_ip = $_SERVER['SERVER_ADDR'] ?? $_SERVER['REMOTE_ADDR'];

			DB::table('serveraddr')->insert([
				"location_id"	=>	$new_location_id,
				'ip_addr'		=>	$server_ip,
				"hw_addr"		=>	$HW_Addr,
				"created_at"	=>	now(),
				'updated_at'	=>	now()
			]);


			$products		 		= $data['products'];
			$thumbnailData			= $data['thumbnailData'];
			$locationPrice			= $data['locationPrice'];
			$prd_category			= $data['prd_category'];
			$prd_subcategory		= $data['prd_subcategory'];
			$prdBrand				= $data['prdBrand'];
			$prd_inventory			= $data['prd_inventory'];

			$productbmatrixbarcode	= $data['productbmatrixbarcode'];
			$productbarcode			= $data['productbarcode'];
			$prdbmatrixbarcodegen	= $data['prdbmatrixbarcodegen'];
            
			if (count($products) > 0) {
				foreach ($products as $p) {
					app('App\Http\Controllers\APIFcController')->insertProduct($p);
				}
			}
			
			if (count($thumbnailData) > 0) {
				foreach ($thumbnailData as $thumbnail) {
					app('App\Http\Controllers\APIFcController')->insertThumbnal($thumbnail);
				}
			}
			
			if (count($locationPrice) > 0) {
				foreach ($locationPrice as $lp) {
					app('App\Http\Controllers\APIFcController')->insertLocationPrice($lp);
				}
			}

		
			if (count($prd_category) > 0) {
				foreach ($prd_category as $cat) {
					app('App\Http\Controllers\APIFcController')->insertPrdCategory($cat);
				}
			}
			
			if (count($prd_subcategory) > 0) {
				foreach ($prd_subcategory as $scat) {
					app('App\Http\Controllers\APIFcController')->insertPrdSubCategory($scat);
				}
			}
			
			if (count($prdBrand) > 0) {
				foreach ($prdBrand as $brand) {
					app('App\Http\Controllers\APIFcController')->insertPrdBrand($brand);
				}
			}
			
			if (count($prd_inventory) > 0) {
				foreach ($prd_inventory as $prd_inv) {
					app('App\Http\Controllers\APIFcController')->insertPrdInventory($prd_inv);
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



//			app('App\Http\Controllers\APIFcController')->push_fc($request);

            return ["status" => true];

        } catch (Exception $e) {
            Log::error([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            return [
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ];
        }
    }

    public function licenceInterfaceActivateTerminal(Request $request)
    {
        try {

            // From URL to get webpage contents.
            $url = env('MOTHERSHIP_URL') .
				'/localaccess/interface/licence-activate-terminal';
            Log::debug("url=" . $url);
			
			//$HW_Addr = $this->getMacLinux();
			$HW_Addr = null; // This is a browser, can't use OS 

            $post = $request->all();
            $post['LOCAL_IPADDR']	= env('LOCAL_IPADDR');

			// There can only be the franchisee's company in the table
            $post['merchant_id']	= DB::table('company')->first()->systemid;

			$post['tsystem']		= env('TSYSTEM');
			$post['HW_Addr']		= $HW_Addr;
			$post['api_key']		= env('APP_KEY');

            Log::debug('post=' . json_encode($post));

            $cURLConnection = curl_init($url);
            curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $post);
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYHOST, false);
            $apiResponse = curl_exec($cURLConnection);
            curl_close($cURLConnection);
            $data = json_decode($apiResponse, true);
            Log::debug('Curl Response: '.$apiResponse);

            if (isset($data['error'])) {
                return $data;
			}

            $client_ip = request()->ip();
            if (!empty($data)) {
                //insert terminal
                $terminal_data = $data['terminal'];
                $terminal_data = [
                    'systemid' => $terminal_data['systemid'],
					//'hw_addr'	=> $HW_Addr,	Obslete
                    'ip_addr' => $terminal_data['ip_addr'],
                    'client_ip' => $client_ip,
                    'mode' => $terminal_data['mode'],
                    'status' => $terminal_data['status'],
                    'taxtype' => $terminal_data['taxtype'],
                    'tax_percent' => $terminal_data['tax_percent'],
                    'servicecharge' => $terminal_data['servicecharge'],
                    'local_logo' => $terminal_data['local_logo'],
                    'ip_addr' => env('LOCAL_IPADDR'),
                    'created_at' => $terminal_data['created_at'],
                    'updated_at' => now()
                ];

                $terminal_condition = [
                    'systemid' => $terminal_data['systemid'],
                ];

                $key = substr($terminal_data['systemid'], -6);
                DB::RAW("CREATE SEQUENCE IF NOT EXISTS receipt_seq_$key nocycle nocache;");

                $new_terminal_id = $this->updateOrInsert('terminal', $terminal_condition, $terminal_data);

				$terminalcount = $data['terminalcount'];
				$terminalcount['terminal_id'] = $new_terminal_id;
				$terminalcount_condition = ['terminal_id' => $new_terminal_id];
                //$terminalcount_id = $this->updateOrInsert('terminalcount', $terminalcount_condition, $terminalcount);
            }

            $lic_key = $request->licensekey;
            $isValidLic = DB::table('lic_terminalkey')->
            join('terminal', 'terminal.id', 'lic_terminalkey.terminal_id')->
            where([
                'lic_terminalkey.license_key' => $lic_key,
                'terminal.systemid' => $request->terminal_id
            ])->first();

            if (empty($isValidLic)) {
                throw new \Exception("Invalid license key");
            }

            DB::table('lic_terminalkey')->
            where('license_key', $lic_key)->update([
                "has_setup" => 1,
                "updated_at" => date("Y-m-d H:i:s")
            ]);
		
            return ["status" => true];

        } catch (\Exception $e) {
            \Log::info([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            return [
                "error" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine()
            ];
        }
	}


	// Grab the first non Wifi NIC
	function getMacLinux() {
		Log::debug('*** getMacLinux() ***');

		$mac  = shell_exec("/bin/ip link | /usr/bin/awk '$2 ~ /^[Ee]/ {getline;print}' | /bin/sed -e 's/^ *//g' | /usr/bin/cut -d' ' -f2 | /usr/bin/head -1");

		Log::debug('mac='.$mac);

		return trim($mac);
	}


    public function updateData(Request $request)
    {
        try {
            \Log::info('#### updateData(Request $request) ' . rand() . ' #####');


            if (!empty($request->users)) {
                $users = json_decode($request->users, true);
                foreach ($users as $user_data) {
                    $user_data = [
                        'id' => $user_data['id'],
                        "systemid" => $user_data['systemid'],
                        "email" => $user_data['email'],
                        'fullname' => $user_data['name'],
                        'username' => $user_data['systemid'],
                        'password' => $user_data['password'],
                        'status' => $user_data['status'],
                        'access_code' => 12345, // Warning: Hardcode
                        'created_at' => $user_data['created_at'],
                        'updated_at' => $user_data['updated_at']
                    ];

                    $user_condition = [
                        "systemid" => $user_data['systemid']
                    ];

                    $new_user_id = $this->updateOrInsert('users', $user_condition, $user_data);
                }
            }

            if (!empty($request->terminal_data)) {
                $terminal = json_decode($request->terminal_data, true);
                if (!empty($terminal)) {
                    foreach ($terminal as $terminal_data) {

                        $terminal_data = [
                            'systemid' => $terminal_data['systemid'],
                            'ip_addr' => $terminal_data['ip_addr'],
                            'mode' => $terminal_data['mode'],
                            'status' => $terminal_data['status'],
                            'taxtype' => $terminal_data['taxtype'],
                            'tax_percent' => $terminal_data['tax_percent'],
                            'servicecharge' => $terminal_data['servicecharge'],
                            'local_logo' => $terminal_data['local_logo'],
                            'ip_addr' => env('OCEANIA_IPADDR'),
                            'created_at' => $terminal_data['created_at'],
                            'updated_at' => $terminal_data['updated_at']
                        ];

                        $terminal_condition = [
                            'systemid' => $terminal_data['systemid'],
                        ];

                        $new_terminal_id = $this->updateOrInsert('terminal', $terminal_condition, $terminal_data);
                    }
                }
            }

            if (!empty($request->lic_terminalkey)) {
                $lic_terminalkey = json_decode($request->lic_terminalkey, true);

                if (!empty($lic_terminalkey)) {
                    foreach ($lic_terminalkey as $terminal_key) {

                        $terminal_id = DB::table('terminal')->
                        where('systemid', $terminal_key['systemid'])->
                        first()->id;

                        $lic_terminal_condition = [
                            'terminal_id' => $terminal_id,
                        ];

                        unset($terminal_key['systemid']);

                        $terminal_key['terminal_id'] = $terminal_id;
                        $terminal_key['has_setup'] = 0;

                        $this->updateOrInsert('lic_terminalkey',
                            $lic_terminal_condition, $terminal_key);
                    }
                }
            }

            if (!empty($request->terminalcount)) {
                $terminalcount = json_decode($request->terminalcount, true);

                if (!empty($terminalcount)) {
					foreach ($terminalcount as $count_data) {

                        $terminal_id = DB::table('terminal')->
							where('systemid', $count_data['systemid'])->
							first()->id;

                        unset($count_data['systemid']);
						$terminalcount_condition = [
                            'terminal_id' => $terminal_id,
                        ];
						
                        $count_data['terminal_id'] = $terminal_id;
						$this->updateOrInsert('terminalcount',$terminalcount_condition, $count_data);
					}
				}
			}

            if (!empty($request->delete)) {
                $delete_loc = json_decode($request->delete, true);
                Log::info([
                    "DELETE" => $delete_loc,
                    "SQL" => DB::table($delete_loc['table'])->where($delete_loc['condition'])->toSql()
                ]);
                DB::table($delete_loc['table'])->where($delete_loc['condition'])->delete();
            }

            return ["status" => true];

        } catch (\Exception $e) {
            \Log::info([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);

            return [
                "error" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine()
            ];
        }
    }

    ///////////////////////////////////////////
    // Helper Function
    ///////////////////////////////////////////

    private function updateOrInsert($tableName, $targetCondition, $targetData)
    {
		Log::debug('tableName='.json_encode($tableName));
		Log::debug('targetCondition='.json_encode($targetCondition));
		Log::debug('targetData='.json_encode($targetData));

        //	try {
        if (isset($targetData['id']))
            unset($targetData['id']);
        $targetTable = DB::table($tableName);

        $shouldInsert = $targetTable->
        where($targetCondition)->
        first();

        if (empty($shouldInsert)) {
            $targetId = $targetTable->insertGetId($targetData);
        } else {
		   if (!empty($targetData['updated_at'])) {
			$targetCondition['updated_at'] = $targetData['updated_at'];

            $shouldUpdate = $targetTable->
            where($targetCondition)->
            first();

            unset($targetCondition['updated_at']);

            if (empty($shouldUpdate)) {
                DB::table($tableName)->where($targetCondition)->update($targetData);
                \Log::info(["targetCondition" => $targetCondition]);
			}

	            $targetId = $shouldInsert->id ?? 0;
		   } else {
		   		\Log::info([
					"error record" => json_encode($targetData)
				]);
		   }
        }

        return $targetId;

        //	}	catch (\Exception $e) {
        //		$this->handleError($e, $e->getCode());
        //	}
    }

    private function handleError(\Exception $e, $error_code = 403)
    {
        \Log::info([
            "Error" => $e->getMessage(),
            "File" => $e->getFile(),
            "Line" => $e->getLine()
        ]);

        abort(response()->json(
            ['message' => $e->getMessage()], 404));
    }

    public function updateReceiptatMotherShip($payload)
    {
        $url = env('MOTHERSHIP_URL') . '/localaccess/interface/eod-receiptupdate';
        Log::debug("url=" . $url);

		
		$post['api_key']		= env('APP_KEY');
        $post['LOCAL_IPADDR'] = env('LOCAL_IPADDR');

        Log::debug('post=' . json_encode($post));

        $cURLConnection = curl_init($url);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER,array('Content-Type:application/json'));
        curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYHOST, false);
        $apiResponse = curl_exec($cURLConnection);
        curl_close($cURLConnection);

        $data = json_decode($apiResponse, true);

        return $data;
    }
}
