<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommReceipt extends Model
{
    protected $table = "comm_receipt";
    public function location()
    {
        return $this->belongsTo('App\Models\Location');
    }
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }


    public function user()
    {
        return $this->belongsTo('App\Models\User','staff_user_id','id');
    }

}
