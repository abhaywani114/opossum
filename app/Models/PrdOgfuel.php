<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrdOgfuel extends Model
{
    protected $guarded=['id'];
    protected $table = "prd_ogfuel";

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
