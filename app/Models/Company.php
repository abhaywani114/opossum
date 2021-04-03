<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    //
    protected $table = "company";
    protected $fillable = ["systemid","id" ,
                            "name",
                            "business_reg_no",
                            "corporate_logo" ,
                            "owner_user_id" ,
                            "gst_vat_sst" ,
                            "currency_id" ,
                            "office_address",
                            "status" ];


    public function currency()
    {
        return $this->hasOne('App\Models\Currency', 'id', 'currency_id');
    }

    public function owner_user()
    {
        return $this->belongsTo('App\Models\User', 'owner_user_id', 'id');
    }



}
