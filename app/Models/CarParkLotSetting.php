<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarParkLotSetting extends Model
{
    protected $guarded=['id'];
    protected $table = "carparklot_setting";
    protected $fillable = ["default_rate"];

    public function carparkoper()
    {
        return $this->belongsTo('App\Models\CarparkOper');
    }
}
