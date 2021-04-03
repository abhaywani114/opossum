<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    //
    protected $table = "currency";


    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'id', 'currency_id');
    }

}
