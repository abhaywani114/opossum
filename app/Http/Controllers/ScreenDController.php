<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Models\CommReceipt;
use App\Models\Currency;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Companycontact;
use App\Models\Companydirector;
use App\Models\Location;
use App\Models\Terminal;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Carbon;

class ScreenDController extends Controller
{
   /* public function __construct()
    {
        $this->middleware('auth');
    }*/

    public function screen_d()
    {
        $ip = env('MINISTN_IPADDR');
        $user = Auth::user();
        $client_ip = request()->ip();
        $merchant = Company::first();
        $location = Location::first();
        $terminal = Terminal::where('client_ip', $client_ip)->first();
        return view('screen_d.screen_d', compact('terminal', 'location', 'user', 'merchant'));
    }

    public function screen_oceania_d()
    {
        $ip = env('MINISTN_IPADDR');
        $user = Auth::user();
        $merchant = Company::first();
        $location = Location::first();

		$client_ip = request()->ip();
        $terminal = DB::table('terminal')->where('client_ip', $client_ip)->first();
      
        return view('oceania_svr.screen_d.screen_d', compact('terminal', 'location', 'user', 'merchant'));

    }

    public function local_fuelprice()
    {
        $push = DB::table('og_localfuelprice')
                            ->select('push_date')
                            ->orderBy('push_date', 'DESC')
                            ->first();

        $date = $push->push_date ?? null;

        return view('local_fuelprice.local_fuelprice', compact('date'));
    }

    public function fuel_movement()
    {
        $company = Company::first();
        $location = Location::first();

        $client_ip = request()->ip();
        $terminal = DB::table('terminal')->where('client_ip', $client_ip)->first();
        $set_update = date("Y-m-d", strtotime(DB::table('lic_terminalkey')->
            where(['has_setup' => 1, "terminal_id" => $terminal->id])->
            first()->created_at ?? now()));

        return view('fuel_movement.fuel_movement', compact('location', 'company', 'set_update'));
    }

    public function settingindex()
    {
        $company = Company::first();
        $companydirector = null;
        $location = null;
        if ($company) {
            $location = Location::first();
            $companydirector = Companydirector::where('company_id', $company->id)->get();
            $companycontact = Companycontact::where('company_id', $company->id)->get();
        } else {
            $company = array("msg" => "Company record not found");
        }

        return view('setting.setting', compact('company', 'location', 'companydirector', 'companycontact'));
    }

    public function getCurrency()
    {
        $company = Company::first();
        $currencys = DB::table('currency')->orderBy('code')->get();
        return view('screen_d.currency', compact('currencys', 'company'));
    }

    public function setCurrency(Request $request)
    {
        $company = Company::first();
        if (!$company) {
            $company = new Company;
            $company->status = "pending";
        }
        $company->currency_id = $request->currency_id;
        $company->save();
        return true;

    }


    public function getRate()
    {

		$client_ip = request()->ip();
        $terminal = DB::table('terminal')->where('client_ip', $client_ip)->first();
       
        return view('screen_d.rate', compact('terminal'));
    }


    public function setRate(Request $request)
    {
		$client_ip = request()->ip();
        //$terminal = DB::table('terminal')->where('client_ip', $client_ip)->first();
		$terminal = Terminal::where('client_ip', $client_ip)->first();
        $terminal->mode = $request->mode;
        $terminal->taxtype = $request->taxtype;
        $terminal->tax_percent = $request->tax_percent;
        // $terminal->servicecharge =$request->servicecharge;
        $terminal->save();
        return true;
    }


    public function setLogo(Request $request)
    {
        if ($request->hasfile('file')) {
            $file = $request->file('file');

            // getting image extension
            $extension = $file->getClientOriginalExtension();
            if (!in_array($extension, array(
                'jpg', 'JPG', 'png', 'PNG', 'jpeg',
                'JPEG', 'gif', 'GIF', 'bmp', 'BMP',
                'tiff', 'TIFF'))) {
                return abort(403);
            }

            $company = Company::first();
            $id = $company->id;
            $name = $request->file('file')->getClientOriginalName();
            $request->file->move(public_path('images/company/' . $id .
                '/corporate_logo'), $name);

            if (!$company) {
                $company = new Company;
                $company->status = "pending";
            }
            $company->corporate_logo = $name;
            $company->save();
            $return_arr = array(
                "name" => $name,
                "size" => 000,
                "src" => "images/company/$id/corporate_logo/$name");

            return response()->json($return_arr);

        } else {
            return abort(403);
        }
    }


    public function delLogo()
    {
        $company = Company::first();
        $return_val = true;
        if ($company) {
            if (is_file(public_path('images/company/' . $company->id .
                '/corporate_logo/' . $company->corporate_logo))) {

                // unlink(public_path('images/company/'.$company->id.
                // 	'/corporate_logo/'.$company->corporate_logo));
                $company = Company::first();
                $company->corporate_logo = null;
                $company->save();

            } else {
                $return_val = "image not found";
            }
            return $return_val;
        }
        return $return_val;
    }


    public function saveCompanydetails(Request $request)
    {
        $company = Company::first();
        $id = $company->id;
        if (!$company) {
            $company = new Company;
            $company->status = "pending";
        }
        $company->name = $request->name;
        $company->business_reg_no = $request->business_reg_no;
        $company->gst_vat_sst = $request->gst_vat_sst;
        $company->office_address = $request->office_address;
        $company->save();

        if ($request->director_new) {
            foreach ($request->director_new as $row) {
                $companydirector = new Companydirector();
                $companydirector->name = $row['name'];
                $companydirector->nric = $row['nric'];
                $companydirector->company_id = $id;
                $companydirector->save();
            }
        }
        if ($request->director_old) {
            foreach ($request->director_old as $row) {
                $companydirector = Companydirector::find($row['id']);
                if ($companydirector) {
                    $companydirector->name = $row['name'];
                    $companydirector->nric = $row['nric'];
                    $companydirector->save();
                }

            }
        }
        if ($request->contact_new) {
            foreach ($request->contact_new as $row) {
                $companycontact = new Companycontact();
                $companycontact->name = $row['name'];
                $companycontact->mobile = $row['mobile'];
                $companycontact->company_id = $id;
                $companycontact->save();
            }
        }
        if ($request->contact_old) {
            foreach ($request->contact_old as $row) {
                $companycontact = Companycontact::find($row['id']);
                if ($companycontact) {
                    $companycontact->name = $row['name'];
                    $companycontact->mobile = $row['mobile'];
                    $companycontact->save();
                }
            }
        }
        return true;
    }


    public function saveLocationName(Request $request)
    {
        $location = Location::first();
        $location->name = $request->name;
        $location->save();
        return true;
    }


    public function saveTime(Request $request)
    {
        $location = Location::first();
        $location->start_work = $request->start_work;
        $location->close_work = $request->close_work;
        $location->save();
        return true;
    }

    public function dltDirector(Request $request)
    {
        $return_valr = false;
        $companydirector = Companydirector::find($request->d_id);
        if ($companydirector) {
            $companydirector->delete();
            $return_valr = true;
        }

        return $return_valr;
    }

    public function dltContact(Request $request)
    {
        $return_valr = false;
        $companycontact = Companycontact::find($request->c_id);
        if ($companycontact) {
            $companycontact->delete();
            $return_valr = true;
        }

        return $return_valr;
    }


    public function cstore(Request $request)
    {
		$client_ip = request()->ip();
        $terminal = DB::table('terminal')->where('client_ip', $client_ip)->first();
        
        $company_currency = Company::latest('id')->first()->currency_id;

        $company = Company::first();
        $currencyarr = DB::table('currency')->where('id',$company->currency_id)->orderBy('code')->get()->first();



		// if (!empty($company_currency)) {
		// 	try {
		// 		$currency = Currency::where('id',$company_currency)->
		// 			first()->code;
		// 	} catch (Exception $e) {
		// 		// Couldn't get currency, set to default
		// 		$currency = 'MYR';
		// 	}
		// } else {
		// 	$currency = 'MYR';
		// }
        $currency = $currencyarr->code ?? 'MYR';
        

        if ($request->amount) {
            $productid = $request->product_id;
            $product = DB::table('product')->where('id', $productid)->first();

            //$result2 = DB::select(DB::raw($query2));
            //$price = $result2[0]->price/100;
            $ogFuel = DB::table('prd_ogfuel')->
            where('product_id', $product->id)->first();

            $price = app("App\Http\Controllers\LocalFuelController")->
            getPrice($ogFuel->id);

            $qty = $request->amount / $price;
            $productData = array("product_amount" => $request->amount,
                "product_quantity" => $qty, "price" => $price);

        } else {
            $productData = "";
            $product = "";
        }

		$product_data = $this->screen_a_products(null); 
		
        return view("cstore.cstore", compact(
            "productData", "product", "terminal","currency", 'product_data'
        ));
	}

public function getproduct(Request $request)
    {

        $a = (int) $request->product_id;
        $product_data = DB::table('product')->
                leftjoin('localprice', 'localprice.product_id', 'product.id')->
                select("product.*", 'localprice.recommended_price')->
                whereNotNull(['product.name','product.thumbnail_1'])->
                whereNotIn('product.ptype', ['oilgas'])->
                where('product.id', $a)->
                get();
        //$product_data  = $product_data->merge($product_data_open_item);
         $data = view('cstore.screen_a_products', compact('product_data'))->render();
         return response()->Json($data);

    }
	public function screen_a_products($a = null) {

		if (empty($a)) 
			$product_data =	DB::table('product')->
            	leftjoin('localprice', 'localprice.product_id', 'product.id')->
				where('localprice.active', '1')->
				whereNotIn('product.ptype', ['oilgas'])->
				select("product.*", 'localprice.recommended_price')->
				get();
		else
			$product_data =	DB::table('product')->
            	leftjoin('localprice', 'localprice.product_id', 'product.id')->
				select("product.*", 'localprice.recommended_price')->
				where('localprice.active', '1')->
				whereNotIn('product.ptype', ['oilgas'])->
				where('name', 'LIKE', "%$a%")->
				get();

		if (empty($a)) 
			$product_data_open_item = DB::table('product')->
				join('prd_openitem','prd_openitem.product_id','product.id')->
				whereNotNull(['product.name','product.thumbnail_1'])->
				select("product.*", "prd_openitem.price as recommended_price")->
				get();
		else
			$product_data_open_item = DB::table('product')->
				join('prd_openitem','prd_openitem.product_id','product.id')->
				whereNotNull(['product.name', 'product.thumbnail_1'])->
				where('product.name', 'LIKE', "%$a%")->
				select("product.*", "prd_openitem.price as recommended_price")->
				get();

			$product_data  = $product_data->merge($product_data_open_item)->unique('systemid');

			return view('cstore.screen_a_products', compact('product_data'))->render();
	}
	
	public function scan_product($a = null) {
		
		$product_data =	DB::table('product')->
            	leftjoin('localprice', 'localprice.product_id', 'product.id')->
				select("product.*", 'localprice.recommended_price')->
				where('localprice.active', '1')->
				whereNotIn('product.ptype', ['oilgas'])->
				where('product.systemid', $a)->
				first();

		if (empty($product_data))
			abort(404);

		return response()->Json($product_data);

	}


}
