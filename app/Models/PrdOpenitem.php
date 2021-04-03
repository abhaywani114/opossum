<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrdOpenitem extends Model
{
    protected $guarded=['id'];
    protected $table = "prd_openitem";

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
