<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fuelledger extends Model
{
    //
    protected $table = "fuelledger";


    public function location()
    {
        return $this->hasOne('App\Models\Location', 'id', 'location_id');
    }

}
