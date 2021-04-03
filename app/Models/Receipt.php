<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    //
    protected $table = "receipt";


    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'void_user_id');
    }

    public function receiptdetails(){
        return $this->hasOne(ReceiptDetails::class, 'receipt_id','id');
    }
}
