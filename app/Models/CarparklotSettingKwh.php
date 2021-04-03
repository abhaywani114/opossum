<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarparklotSettingKwh extends Model
{
    protected $guarded=['id'];
    protected $table = "carparklot_setting_kwhs";
    protected $fillable = ["default_kwh"];

    public function carparkoper()
    {
        return $this->belongsTo('App\Models\CarparkOper');
    }
}
