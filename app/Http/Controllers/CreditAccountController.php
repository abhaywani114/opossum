<?php

namespace App\Http\Controllers;

use App\Classes\SystemID;
use App\Models\Company;
use App\Models\MerchantLink;
use App\Models\MerchantLinkRelation;
use App\Models\Oneway;
use App\Models\Onewaylocation;
use App\Models\Onewayrelation;
use App\Models\User;
use DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class CreditAccountController extends Controller
{
    function creditAccount()
    {
        try {
            // $company = Company::first();
            //      $userId = $company->owner_user_id;
            //     $responderIds = DB::table('merchantlink')->where('initiator_user_id',
            //         $userId)->pluck('responder_user_id')->toArray();

            //     $initiatorIds = DB::table('merchantlink')->where('responder_user_id',
            //         $userId)->pluck('initiator_user_id')->toArray();

            //     $merchantUserIds = array_merge($responderIds, $initiatorIds);
            //     print_r($merchantUserIds);
            //     exit();


            $test = null;

            return view('credit_ac.credit_ac',
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

    public static function getUserCompany($user_id)
    {
        $company = Company::where("owner_user_id", $user_id)->first();
        return $company;
    }


    function deleteMerchantLinkWithRelation(Request $request)
    {
        try {
            MerchantLink::find($request->id)->delete();
            MerchantLinkRelation::where("merchantlink_id", $request->id)->delete();
            return ["message" => "deleting done", "error" => false];
        } catch (\Exception $e) {
            return ["message" => $e->getMessage(), "error" => true];
        }
    }

    function oneWayDeleteMerchantLinkWithRelation(Request $request)
    {
        try {
           // Log::info($request->all());
            Oneway::find($request->companyId)->delete();
            Onewayrelation::where('oneway_id', $request->companyId)->delete();
            return ["message" => "deleting done", "error" => false];
        } catch (\Exception $e) {
            return ["message" => $e->getMessage(), "error" => true];
        }
    }

    function editMerchantLinkRelation(Request $request)
    {
        try {
            MerchantLinkRelation::where("merchantlink_id", $request->merchantLinkId)
                ->where("ptype", $request->ptype)
                ->update(["status" => $request->status]);
            return ["message" => "update done", "error" => false];
        } catch (\Exception $e) {
            return ["message" => $e->getMessage(), "error" => true];
        }
    }

    function  getAllData(){
        $user_connected_id = Auth::user()->id;
        $merchantLink = MerchantLink::with(["merchantLinkRelation.company.owner_user", "initiator_user.user_company", "responder_user.user_company",])
            ->where("initiator_user_id", $user_connected_id)
            ->orWhere("responder_user_id", $user_connected_id)
            ->get();
        $company = self::getUserCompany($user_connected_id);
        $onewayrelations = OnewayRelation::with(["oneway"])
            ->whereHas("oneway", function ($query) use ($company) {
                $query->where("self_merchant_id", $company->id);
            })->get();

        $data = [];

        foreach ($merchantLink as $item) {
            $dealer = null;

            if ($item->initiator_user_id == $user_connected_id) {
                $dealer = $item->responder_user;
            } else {
                $dealer = $item->initiator_user;
            }

            $status = null;
            foreach ($item->merchantLinkRelation as $value) {
                if ($value->ptype == "dealer") {
                    $status = $value->status == null ? "Active" : $value->status;
                    break;
                }
            }

            array_push($data, ["name_company" => $dealer->user_company->name, "status" => $status, "sysid" => $dealer->user_company->systemid,"type"=>"twoway"]);
        }

        foreach ($onewayrelations as $dealer)
        {

            array_push($data, ["name_company" => $dealer->oneway->company_name,
                "status" => $dealer->status, "sysid" => "-","type"=>"oneway"]);

        }

        return $data;
    }

    function creditAccountList()
    {
        try {

            $data = $this->getAllData();

            //dd($data);
           // Log::info($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);

        } catch (\Exception $e) {
            return ["message" => $e->getMessage(), "error" => false];
        }
    }

    function listMerchantActive()
    {
        try {

            $finalListMerchant =  $data = $this->getAllData();

            //dd($data);
            // Log::info($data);
            return ["data" => $finalListMerchant, "error" => false];

        } catch (\Exception $e) {
            return ["message" => $e->getMessage(), "error" => false];
        }
    }


    function creditAccountListLedger()
    {
        try {

            return DataTables::of([])
                ->addIndexColumn()
                ->make(true);


        } catch (\Exception $e) {
            return ["message" => $e->getMessage(), "error" => false];
        }
    }

    function creditAccountLedger($systemid)
    {
        try {

            $test = null;

            return view('credit_ac.credit_ac_ledger',
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

    function changeOnewayRelationStatus(Request $request){
        Log::info($request->all());
        $onewayRelation = Onewayrelation::where("oneway_id",$request->input('merchantLinkRelationId'))->first();
        $onewayRelation->status = $request->input('status');
        $onewayRelation->save();
        return response()->json(['msg' => 'Status updated successfully', 'status' => 'true']);
    }

    function saveMerchandLink(Request $request){
        $item = json_decode($request->mlink);
        $user_connected_id = $request->user_id;
        Log::info("link count ".json_encode(MerchantLink::whereId($item->id)->count()));
        if (MerchantLink::whereId($item->id)->count() == 0) {
            $dealer = null;
            if ($item->initiator_user_id == $user_connected_id) {
                $dealer = $item->responder_user;
            } else {
                $dealer = $item->initiator_user;
            }

            if (!User::whereEmail($dealer->email)->first()) {
                $systemid = SystemID::openitem_system_id(1);
                User::create([
                    "systemid" => $systemid,
                    "id" => $dealer->id,
                    "email" => $dealer->email,
                    "type" => $dealer->type,
                    "status" => $dealer->status,
                ]);
            }


            if (Company::whereId($dealer->user_company->id)->count() == 0) {
                try{
                    Company::create([
                        "systemid" => $dealer->user_company->systemid,
                        "id" => $dealer->user_company->id,
                        "name" => $dealer->user_company->name,
                        "business_reg_no" => $dealer->user_company->business_reg_no,
                        "corporate_logo" => $dealer->user_company->corporate_logo,
                        "owner_user_id" => $dealer->user_company->owner_user_id,
                        "gst_vat_sst" => $dealer->user_company->gst_vat_sst,
                        "currency_id" => $dealer->user_company->currency_id,
                        "office_address" => $dealer->user_company->office_address,
                        "status" => $dealer->user_company->status,
                    ]);
                }catch (QueryException $e){
                    $errorCode = $e->errorInfo[1];
                    if($errorCode == 1062){
                        // Log::info("Company already exist");
                    }
                }
            }

            \Illuminate\Support\Facades\DB::table('merchantlink')->insert(
                [
                    "id" => $item->id,
                    "initiator_user_id" => $item->initiator_user_id,
                    "responder_user_id" => $item->responder_user_id,
                    "status" => $item->status
                ]
            );

            for ($m = 0; $m < sizeof($item->merchant_link_relation); $m++) {
                $mlr = $item->merchant_link_relation[$m];
                if (MerchantLinkRelation::whereId($mlr->id)->count() == 0) {

                    \Illuminate\Support\Facades\DB::table('merchantlinkrelation')->insert(
                        [
                            "id" => $mlr->id,
                            "company_id" => $mlr->company_id,
                            "merchantlink_id" => $mlr->merchantlink_id,
                            "default_location_id" => $mlr->default_location_id,
                            "ptype" => $mlr->ptype,
                            "status" => $mlr->status,
                        ]
                    );
                }
            }
        }


        return response()->json(['msg' => 'Save successfully', 'status' => 'true']);
    }


    function saveMerchandLinkOneWay(Request $request){
        $item = json_decode($request->mlink);
        $user_connected_id = $request->user_id;
        $companyId = $request->companyId;
        $oneway = $item;
        //Log::info($oneway);
        if ($companyId=='') {
            if (Oneway::whereId($oneway->id)->count() == 0) {
                Oneway::create([
                    "id" => $oneway->id,
                    "self_merchant_id" => $oneway->self_merchant_id,
                    "company_name" => $oneway->company_name,
                    "business_reg_no" => $oneway->business_reg_no,
                    "address" => $oneway->address,
                    "contact_name" => $oneway->contact_name,
                    "mobile_no" => $oneway->mobile_no,
                    "status" => $oneway->status,
                ]);
                $onewayrelation = $item->onewayrelation;
                if (Onewayrelation::whereId($onewayrelation->id)->count() == 0) {
                    Onewayrelation::create([
                        "oneway_id" => $onewayrelation->oneway_id,
                        "default_location_id" => $onewayrelation->default_location_id,
                        "ptype" => $onewayrelation->ptype,
                        "status" => $onewayrelation->status,
                    ]);
                }
                $onewaylocation = $item->onewaylocation;
                if (Onewaylocation::whereId($onewaylocation->id)->count() == 0) {
                    Onewaylocation::create([
                        "oneway_id" => $onewaylocation->oneway_id,
                        "location_id" => $onewaylocation->location_id,
                        "deleted_at" => $onewaylocation->deleted_at
                    ]);
                }

            }
        }else{

            Oneway::whereId($companyId)->update([
                "self_merchant_id" => $oneway->self_merchant_id,
                "company_name" => $oneway->company_name,
                "business_reg_no" => $oneway->business_reg_no,
                "address" => $oneway->address,
                "contact_name" => $oneway->contact_name,
                "mobile_no" => $oneway->mobile_no,
                "status" => $oneway->status,
            ]);
        }


        return response()->json(['msg' => 'Save successfully', 'status' => 'true']);
    }


}
