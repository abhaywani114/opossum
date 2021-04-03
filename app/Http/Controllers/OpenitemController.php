<?php

namespace App\Http\Controllers;

use App\Classes\SystemID;
use App\Models\MerchantPrdCategory;
use App\Models\MerchantProduct;
use App\Models\PrdBrand;
use App\Models\PrdCategory;
use App\Models\PrdOpenitem;
use App\Models\PrdPrdCategory;
use App\Models\PrdSubCategory;
use App\Models\Product;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;
use DB;


class OpenitemController extends Controller
{
    public static $IMG_PRODUCT_LINK = "images/product/";
    function openitem()
    {
        try {

            $test = null;

            return view('openitem.openitem_landing',
                compact('test'));

        } catch (Exception $e) {
            Log::error([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            abort(404);
        }
    }



    function save()
    {
        try {
			// WARNING: Hardcoding location_id=1
            $systemid = SystemID::openitem_system_id(1);
            $product = Product::create(["systemid" => $systemid, "name" => null,'ptype' => 'openitem']);
            $prdOpenitem = PrdOpenitem::create(["product_id" => $product->id,"price"=>0.00,"qty"=>0,"loyalty"=>0,]);
            if (Auth::user()!=null)
            {
                MerchantProduct::create(["product_id"=>$product->id,"merchant_id"=>Auth::user()->id,]);
            }
            return ["data" => $prdOpenitem, "error" => false];

        } catch (Exception $e) {
            return ["message" => $e->getMessage(), "error" => false];
        }
    }


    function listPrdOpenitem()
    {

        try {
			
			PrdOpenitem::get()->map(function($f) {
					$f->qty = app("App\Http\Controllers\CentralStockMgmtController")->
						qtyAvailable($f->product_id);
					$f->update();
				});

			$data = PrdOpenitem::has('product')->with("product")->select('*')->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('qty', function ($data) {
                    $qty = $data;
                    return $qty;
                })
                ->editColumn('loyalty', function ($data) {
                    $loyalty = $data;
                    return $loyalty;
                })
                ->editColumn('price', function ($data) {
                    $price = $data;
                    return $price;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a  href="javascript:void(0)" onclick="deleteMe('.$row->id.')" data-row="'.$row->id.'" class="delete"> <img width="25px" src="images/redcrab_50x50.png" alt=""> </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);


        } catch (Exception $e) {
            return ["message" => $e->getMessage(), "error" => false];
        }
    }



    function detailProduct(Request $request)
    {

        try {
            $product_details = Product::whereId($request->id)->first();
            /*$product_category = PrdCategory::all();
            $product_brand = PrdBrand::all();
            $product_subcategory = PrdSubCategory::all();
			$product_product = PrdPrdCategory::all();*/
            return view("openitem.product_details", compact("product_details"));

        } catch (Exception $e) {
            return ["message" => $e->getMessage(), "error" => false];
        }
    }

    function updateCustom(Request $request)
    {
        try {
            $data = [
                "name"=>$request->product_name==null?null:$request->product_name,
            ];
			
			if (Auth::user()!=null)
			{
				$merchant = DB::table('company')->first();
                MerchantPrdCategory::create(["category_id"=> 0 ,"merchant_id"=>$merchant->id]);
            }

            $prd = Product::where("systemid", $request->systemid)->update($data);
            return ["data" => $prd, "error" => false];
        } catch (Exception $e) {
            return ["message" => $e->getMessage(), "error" => false];
        }

    }


    function get_dropDown($OPTION, $KEY)
    {
        $data = [];
        if ($OPTION == "subcat") {

            $data = PrdSubCategory::where("category_id", $KEY)->get();

        } else {
            $data = PrdPrdCategory::where("subcategory_id", $KEY)->get();
        }

        return $data;
    }


    function delPicture(Request $request)
    {
        try {
            $data = [
                "thumbnail_1"=>null,
                "photo_1"=>null,
            ];

            $prd = Product::where("systemid", $request->systemid)->update($data);
            return ["data" => $prd, "error" => false];
        } catch (Exception $e) {
            return ["message" => $e->getMessage(), "error" => false];
        }

    }


    function savePicture(Request $request)
    {
        try {

            if ($request->file!=null)
            {
                $filename = $this->generatePhotoName($request->file->getClientOriginalExtension());
                $request->file->move(public_path(self::$IMG_PRODUCT_LINK.$request->product_id."/"), $filename);
                $path = public_path(self::$IMG_PRODUCT_LINK.$request->product_id."/")."thumb/";
                if (!file_exists($path)) {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }
                $thumb_path = public_path(self::$IMG_PRODUCT_LINK.$request->product_id."/")."thumb/"."thumb_".$filename;
                File::copy(public_path(self::$IMG_PRODUCT_LINK.$request->product_id."/".$filename),
                    $thumb_path);
                $img = Image::make($thumb_path);
                $img->resize(200, 200, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($thumb_path);

                $data["photo_1"] = $filename;
                $data["thumbnail_1"] = "thumb_".$filename;
            }

            Product::where("systemid", $request->product_id)->update($data);
            $prd = Product::where("systemid", $request->product_id)->first();
            return ["name" => $prd->name,"src"=>self::$IMG_PRODUCT_LINK.$request->product_id."/".$filename, "error" => false];
        } catch (Exception $e) {
            return ["message" => $e->getMessage(), "error" => false];
        }
    }


    function updateOpen(Request $request)
    {
        $data = [
            $request->key => $request->value,
        ];

        $prdOpen = PrdOpenitem::where("id", $request->element)->update($data);
        return ["data" => $prdOpen, "error" => false];
    }


    function deleteOpen(Request $request)
    {

        $prdOpen = PrdOpenitem::where("id", $request->id)->first();
        Product::find($prdOpen->product_id)->delete();
        PrdOpenitem::find($request->id)->delete();
        return ["data" => $prdOpen, "error" => false];
    }

    function generatePhotoName($ext){
        return "p".time()."-m".$this->generateRandomString(14).".".$ext;
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

    function prdLedger($systemid)
    {
        try {

            $product = Product::where("systemid",$systemid)->first();
			
			$location = Location::first();
			$data = collect();

			DB::table('receipt')->
				select('receipt.*', 'receiptproduct.quantity as quantity', 'receiptdetails.id as receiptdetails_id')->
				join('receiptproduct','receipt.id','receiptproduct.receipt_id')->
				leftJoin('receiptdetails','receipt.id','receiptdetails.receipt_id')->
				orderBy('receipt.updated_at', "desc")->
				where("receiptproduct.product_id",$product->id)->
				get()->
				map(function($product) use ($data) {
					$packet = collect();
					$packet->id			= $product->id;
					$packet->status 	= $product->status;
					$packet->systemid	= $product->systemid;
					$packet->quantity	= $product->quantity * -1;
					$packet->created_at = $product->created_at;
					$packet->voided_at	= $product->voided_at;
					$packet->doc_type	= "Cash Sales";
					$data->push($packet);
				});

			DB::table('stockreportproduct')->
				leftjoin('stockreport','stockreport.id','stockreportproduct.stockreport_id')->
				where('stockreportproduct.product_id', $product->id)->
				orderBy('stockreport.updated_at', "desc")->
				get()->map(function($product) use ($data) {
					$packet = collect();
					$packet->id			= $product->id;
					$packet->status 	= $product->status;
					$packet->systemid	= $product->systemid;
					$packet->quantity	= $product->quantity;
					$packet->created_at = $product->created_at;
					$packet->voided_at	= $product->voided_at ?? "";
					$packet->doc_type	= ucfirst($product->type);
					$data->push($packet);
				});

			$data = $data->sortByDesc('created_at')->values();

            return view('openitem.openitem_productledger',
                compact('product', 'data', 'location'));

        } catch (Exception $e) {
            Log::error([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            abort(404);
        }
    }


    function openitemStockout()
    {
        try {

            $location = DB::table('location')->first();
            return view('openitem.openitem_stockout', compact('location'));

        } catch (Exception $e) {
            Log::error([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            abort(404);
        }
    }


    function openitemStockin()
    {
        try {

            $location = DB::table('location')->first();
            return view('openitem.openitem_stockin',compact('location'));

        } catch (Exception $e) {
            Log::error([
                "Error" => $e->getMessage(),
                "File" => $e->getFile(),
                "Line" => $e->getLine()
            ]);
            abort(404);
        }
    }



    function stockOutList()
    {

        try {

			$product_data_open_item = DB::table('product')->
				join('prd_openitem','prd_openitem.product_id','product.id')->
				whereNotNull(['product.name','product.thumbnail_1'])->
				select("product.*", "prd_openitem.price as recommended_price")->
				get();
			
			$product_data_open_item = $product_data_open_item->filter(function ($product) {
                return app("App\Http\Controllers\CentralStockMgmtController")->
                    qtyAvailable($product->id) > 0;
            });

            return Datatables::of($product_data_open_item)
                ->addIndexColumn()
				->addColumn('product_systemid', function ($data) {
					return $data->systemid;
				})

				->addColumn('product_name', function ($data) {
					
					$img_src = '/images/product/' .
					$data->systemid . '/thumb/' .
					$data->thumbnail_1;

					$img = "<img src='$img_src' data-field='inven_pro_name' style=' width: 25px;
						height: 25px;display: inline-block;margin-right: 8px;object-fit:contain;'>";

					return $img.$data->name;
				})

				->addColumn('product_qty', function ($data) {
					$product_id = $data->id;
					$qty = app("App\Http\Controllers\CentralStockMgmtController")->
						qtyAvailable($product_id);
					return <<<EOD
						<span id="qty_$product_id">$qty</span>
EOD;
				})

		  		->addColumn('action', function ($data) {
					$product_id = $data->id;
        	    	return view('fuel_stockmgmt.inven_qty', compact('product_id'));
				})
                ->rawColumns(['action'])
				->escapeColumns([])
                ->make(true);




        } catch (Exception $e) {
            return ["message" => $e->getMessage(), "error" => false];
        }
    }


    function stockInList()
    {

        try {
			
			$product_data_open_item = DB::table('product')->
				join('prd_openitem','prd_openitem.product_id','product.id')->
				whereNotNull(['product.name','product.thumbnail_1'])->
				select("product.*", "prd_openitem.price as recommended_price")->
				get();

            return Datatables::of($product_data_open_item)
                ->addIndexColumn()
				->addColumn('product_systemid', function ($data) {
					return $data->systemid;
				})

				->addColumn('product_name', function ($data) {
					
					$img_src = '/images/product/' .
					$data->systemid . '/thumb/' .
					$data->thumbnail_1;

					$img = "<img src='$img_src' data-field='inven_pro_name' style=' width: 25px;
						height: 25px;display: inline-block;margin-right: 8px;object-fit:contain;'>";

					return $img.$data->name;
				})

				->addColumn('product_qty', function ($data) {
					$product_id = $data->id;
					$qty = app("App\Http\Controllers\CentralStockMgmtController")->
						qtyAvailable($product_id);
					return <<<EOD
						<span id="qty_$product_id">$qty</span>
EOD;
				})

		  		->addColumn('action', function ($data) {
					$product_id = $data->id;
        	    	return view('fuel_stockmgmt.inven_qty', compact('product_id'));
				})
                ->rawColumns(['action'])
				->escapeColumns([])
                ->make(true);


        } catch (Exception $e) {
            return ["message" => $e->getMessage(), "error" => false];
        }
    }



}
