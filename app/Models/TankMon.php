<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TankMon extends Model
{
    protected $guarded=['id'];
    protected $table = "tankmon";
    public function tank()
    {
        return $this->belongsTo('App\Models\Tank');
    }
}
