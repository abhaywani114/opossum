<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //
    protected $table = "location";

    public function fuelledger()
    {
        return $this->belongsTo('App\Models\Fuelledger', 'id', 'location_id');
    }



    public  static function getLocation(){
        return Location::orderBy('id', 'desc')->first();
    }

}
