<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantLinkRelation extends Model
{
    protected $table = "merchantlinkrelation";

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function merchantlink()
    {
        return $this->belongsTo('App\Models\MerchantLink');
    }

}
