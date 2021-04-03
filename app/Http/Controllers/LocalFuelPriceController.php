<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use \App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use \App\Classes\thumb;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\SetupController;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Filesystem\Filesystem as File;



class LocalFuelPriceController extends Controller
{

    public function priceUpdate(Request $request){

        //Log::debug($request);

        $location_id = $request->location_id;
        $franchise_id = $request->franchise_id;
        $company_id = $request->company_id;
        $username = $request->username;
        $product_name = $request->product_name;
        $product_systemid = $request->product_systemid;
        $price = $request->price;
        $userid = $request->userid;
        $prdcategory_id = $request->prdcategory_id;
		$prdsubcategory_id = $request->prdsubcategory_id;
		$ptype = $request->ptype;
        $brand_id = $request->brand_id;
        $time = Carbon::parse($request->time);
        Log::debug($time);
        Log::debug($request->time);
        if(empty($product_systemid)) {
            return response()->json(['error'=>'No details to treat']);
        }

        if($price == null){$price = 000;}

        //Check if UserID exist on users

        $user = User::find($userid);

        if(!$user){
            $new = new User;
            $new->id = $userid;
            $new->fullname = $username;
            $new->systemid = uniqid();
            $new->password = uniqid();

            $new->save();
        }

        //Check if Product Exist
        $prod = DB::table('product')->
			where('systemid', $product_systemid)->first();

        //Create Product if Doesn't Exist
        if($prod == false){
            $set = [
			   'systemid'=>$product_systemid,
			   'name'=>$product_name,
			   'ptype'=>$ptype,
			   'prdcategory_id'=>$prdcategory_id,
			   'prdsubcategory_id'=>$prdsubcategory_id,
			   'brand_id'=>$brand_id,
			];

            $last_id = DB::table('product')->insertGetId($set);
            $data = ['product_id'=>$last_id, 'price'=>$price];
            $pd_id = DB::table('prd_ogfuel')->insertGetId($data);

            DB::table('og_localfuelprice')->insert([
                'ogfuel_id'=>$pd_id,
                'company_id'=>$company_id,
                'location_id'=>$location_id,
                'start'=>$time,
                'price'=>$price,
                'user_id'=>$userid,
                'updated_at'=>$time
            ]);

        } else {

            $id = DB::table('prd_ogfuel')->
				where('product_id', $prod->id)->first();

            DB::table('og_localfuelprice')->
				where('ogfuel_id',$id->id)->
				update([
					'price'=>$price,
					'user_id'=>$userid,
					'updated_at'=>$time,
					'created_at'=>$time,
					'start'=>$time
				]);
        }

        //Return Response
        return response()->json(['success'=>'Price Update Successful']);
    }


    public function updateControllerPrice($datas)
    {
        foreach($datas as $data){
            $price = $data['Price'];
            $id = $data['og_f_id'];
            $sprice = $price * 100;

            DB::table('og_localfuelprice')->
				where('ogfuel_id',$id)->
				update([
					'controller_price'=>$sprice,
					'push_date'=>NOW()
				]);
        }
    }


    public function productUpdate(Request $request){
        $location_id = $request->input('location_id');
        $franchise_id = $request->input('franchise_id');
        $company_id = $request->input('company_id');
        $username = $request->input('username');
        $product_name = $request->input('product_name');
        $product_systemid = $request->input('product_systemid');
        $userid = $request->userid;
        $photo_url = $request->input('photo_1');
        $prdcategory_id = $request->input('prdcategory_id');
		$prdsubcategory_id = $request->input('prdsubcategory_id');
		$ptype = $request->input('ptype');
        $brand_id = $request->input('brand_id');
        $time = Carbon::parse($request->input('time'));
        $price = $request->price;

        $photo = basename($photo_url);
        Log::debug('User ID: '.$userid);
        Log::debug('photo_url='.$photo_url);
        Log::debug('photo='.$photo);

		$arrContextOptions=array(
			"ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			),
		);

        $contents = file_get_contents($photo_url, false,
			stream_context_create($arrContextOptions));
        $path = public_path('images/product/'.$product_systemid);


        if(!file_exists($path)) {
            mkdir($path, 0755, true);

        }

        $file = public_path('images/product/'.$product_systemid.'/').$photo;
        Log::debug('file='.$file);


        $save = file_put_contents($file, $contents);


        $thumbnail ='thumb_' . $photo;

        $path = public_path('images/product/'.$product_systemid.'/thumb/');


        if(!file_exists($path)) {
            mkdir($path, 0755, true);

        }
        $filethumb = public_path('images/product/'.$product_systemid.'/thumb/').$thumbnail;
        Log::debug('file='.$filethumb);
        Log::debug('thumbnail: '.$thumbnail);
        $save = file_put_contents($filethumb, $contents);

        Log::debug($save);
        Log::debug('product_systemid='.json_encode($product_systemid));

        if(empty($product_systemid)) {
            return response()->json(['error'=>'No details to treat']);
        }

        $user = User::find($userid);

        if(!$user){
            $new = new User;
            $new->id = $userid;
            $new->fullname = $username;
            $new->systemid = uniqid();
            $new->password = uniqid();

            $new->save();
        }

        //Check if Product Exist
        $prod = DB::table('product')->where('systemid',
            $product_systemid)->first();

        Log::debug('Product: '.json_encode($prod));


        $file = $request->file('photo_1');

        if($prod == false){
            $set = [
			   'systemid'=>$product_systemid,
			   'name'=>$product_name,
			   'ptype'=>$ptype,
			   'photo_1'=>$photo,
			   'thumbnail_1'=>$thumbnail,
			   'prdcategory_id'=>$prdcategory_id,
			   'prdsubcategory_id'=>$prdsubcategory_id,
			   'brand_id'=>$brand_id,
			];

            $last_id = DB::table('product')->insertGetId($set);

            Log::debug('New Product ID: '.$last_id);

            $data = ['product_id'=>$last_id, 'price'=>000];

            $pd_id = DB::table('prd_ogfuel')->insertGetId($data);

            DB::table('og_localfuelprice')->insert([
                'ogfuel_id'=>$pd_id,
                'company_id'=>$company_id,
                'location_id'=>$location_id,
                'price'=>$price,
                'start'=>$time,
                'user_id'=>$userid,
                'updated_at'=>$time
            ]);

        } else {

            $id = DB::table('prd_ogfuel')->
				where('product_id', $prod->id)->first();

            //Check if Local Fuel Price Exist
            $testprice = DB::table('og_localfuelprice')->where('ogfuel_id',$id->id)->first();
            if(!$testprice){

                    DB::table('og_localfuelprice')->
                        where('ogfuel_id',$id->id)->
                        insert(['ogfuel_id'=>$id->id,
                            'price'=>$price,
                            'company_id'=>$company_id,
                            'user_id'=>$userid,
                            'updated_at'=>$time,
                            'created_at'=>$time,
                            'start'=>$time
                        ]);

            }

            $set = [
                'systemid'=>$product_systemid,
                'name'=>$product_name,
                'ptype'=>$ptype,
                'photo_1'=>$photo,
                'thumbnail_1'=>$thumbnail,
                'prdcategory_id'=>$prdcategory_id,
                'prdsubcategory_id'=>$prdsubcategory_id,
                'brand_id'=>$brand_id,
			];

			$last_id = DB::table('product')->
				where('id', $prod->id)->
				update($set);
        }
    }



}
