<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvreceiptCarpark extends Model
{
    protected $guarded = ['id'];
    protected $table = "evreceiptcarparklot";
    public function evreceipt()
    {
        return $this->belongsTo('App\Models\Evreceipt');
    }
    public function carparklot()
    {
        return $this->belongsTo('App\Models\CarPark',"carparklot_id","id");
    }

}
