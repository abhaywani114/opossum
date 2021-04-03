<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Companycontact;
use App\Models\Companydirector;
use App\Models\Location;
use App\Models\Terminal;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use \Log;

class ScreenEController extends Controller
{
	function screen_e() {

		$merchant = Company::first();
        $location = Location::first();
        $currency = DB::table('currency')->
			where('id',$merchant->currency_id)->
			orderBy('code')->get()->first();

		// Protect against NULL currency
        if (empty($currency)) {
            $currency = DB::table('currency')->
                where('code', 'MYR')->get()->first();
        }

        $cu = (object) array('currency'=>$currency->code);

		$client_ip = request()->ip();
        $terminal = Terminal::where('client_ip', $client_ip)->first();

		$product_ids = DB::table('product')->get()->pluck('id');

		if (!empty($company_details->corporate_logo))
			$logo = "logo/$company_details->id/$company_details->corporate_logo";
		$logo = $logo ?? null;

		return view('screen_e.opossum_e')->with(['terminal' => $terminal, 
			'product_ids' =>   $product_ids, 'cu'=>$cu , 'location' => $location, "logo" => $logo]);
	}


	public function update(Request $request) {
        try {
            $location_id     = $request->location_id;
            $location = location::find($location_id);
			
			if (!$location) {
                throw new Exception("Location not found", 1);

            }

            $changed = false;

            if ($request->has('branch')) {
                if ($location->branch != $request->branch) {
                    $location->branch = $request->branch;
                    $changed = true;
                }
            }

            if ($request->has('address')) {
                if ($location->address_line1 != $request->address) {
                    $location->address_line1 = $request->address;
                    $changed           = true;
                }
            }

            if ($request->has('e_table_header_color')) {
                if ($location->e_table_header_color != $request->e_table_header_color) {
                    $location->e_table_header_color = $request->e_table_header_color;
                    $changed = true;
					$purple = true;
					$msg = "Screen E Details updated";
                }
            }

            if ($request->has('e_bottom_panel_color')) {
                if ($location->e_bottom_panel_color != $request->e_bottom_panel_color) {
                    $location->e_bottom_panel_color = $request->e_bottom_panel_color;
                    $changed = true;
					$purple = true;
					$msg = "Screen E Details updated";
                }
            }

            if ($request->has('e_right_panel_color')) {
                if ($location->e_right_panel_color != $request->e_right_panel_color) {
                    $location->e_right_panel_color = $request->e_right_panel_color;
                    $changed = true;
					$purple = true;
					$msg = "Screen E Details updated";
                }
            }

            if ($changed == true) {
                $location->save();
				$return = ["success" =>	true, "msg" =>	"Information updated successfully"];
            } else {
                return '';
            }

        } catch (\Exception $e) {
            $msg = "Some error occured";
            \Log::error($e);
			$return = ["success" => false, "msg" =>  $e->getMessage()];
		}

		return $return;
    }

	public function saveLocationImage(Request $request)
    {

        try {
            $validation = Validator::make($request->all(), [
                'location_id' => 'required',
            ]);

            if ($validation->fails()) {
                throw new \Exception("validation_error", 19);
            }

            $location = location::first();

            if (!$location) {
                throw new \Exception('location_not_found', 25);
            }

            if ($request->hasfile('file')) {
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension(); // getting image extension
                $company_id = Company::first()->id; 

                if (!in_array($extension, array(
					'jpg', 'JPG', 'png', 'PNG', 'jpeg', 'JPEG', 'gif', 'GIF', 'bmp', 'BMP', 'tiff', 'TIFF',
					'mp4', '3gp', 'avi', 'flv', 'mpeg'
				))) {
                    return abort(403);
                }

                $filename = ('p' . sprintf("%010d", $location->id)) . '-m' . sprintf("%010d", $company_id) . rand(1000, 9999) . '.' . $extension;

                $location_id = $location->id;

                $this->check_location("/images/location/$location_id/");
                $file->move(public_path() . ("/images/location/$location_id/"), $filename);

                $location->e_right_panel_image_file = $filename;
                $location->save();

                $return_arr = array("name" => $filename, "size" => 000, "src" => "/images/location/$location_id/$filename");
                return response()->json($return_arr);
            } else {
                return abort(403);
            }

        } catch (\Exception $e) {

            if ($e->getMessage() == 'validation_error') {
                return '';
            }
            if ($e->getMessage() == 'location_not_found') {
                $msg = "Error occured while uploading, Invalid location selected";
            }
            {
                $msg = "Error occured while uploading picture";
            }

            Log::error(
                "Error @ " . $e->getLine() . " file " . $e->getFile() .
                ":" . $e->getMessage()
            );

            $data = ["success" => false, "msg"	=>	 $msg]; 
        }
        return $data;
    }


	public function deleteLocationImage(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'location_id' => 'required',
            ]);

            if ($validation->fails()) {
                throw new \Exception("validation_error", 19);
            }

            $location = location::where('id', $request->location_id)->first();

            if (!$location) {
                throw new \Exception('location_not_found', 25);
            }
            unlink(public_path() . ("/images/location/$location->id/$location->e_right_panel_image_file"));
            $location->e_right_panel_image_file = null;
            $location->save();
            $return = response()->json(array("deleted" => "True"));

        } catch (\Exception $e) {
            $return = response()->json(array("deleted" => "False"));
        }

        return $return;

    }


    public function check_location($location)
    {
        $location = array_filter(explode('/', $location));
        $path = public_path();

        foreach ($location as $key) {
            $path .= "/$key";

            Log::debug('check_location(): $path='.$path);

            if (is_dir($path) != true) {
                mkdir($path, 0775, true);
            }
        }
    }


 
}
