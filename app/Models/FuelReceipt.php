<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelReceipt extends Model
{
    protected $table = "fuel_receipt";
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'void_user_id', 'id');
    }
}

?>