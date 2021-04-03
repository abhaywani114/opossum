<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oneway extends Model
{
    protected $fillable = [
        'self_merchant_id',
        'company_name',
        'business_reg_no',
        'address',
        'contact_name',
        'mobile_no',
        'id'
    ];

    protected $table = 'oneway';
}
