<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantLink extends Model
{
    protected $table = "merchantlink";

    protected $fillable = [
        "id",
        "initiator_user_id",
        "responder_user_id",
        "status",
        "created_at"
    ];

    public function initiator_user()
    {
        return $this->belongsTo('App\Models\User', 'initiator_user_id', 'id');
    }

    public function responder_user()
    {
        return $this->belongsTo('App\Models\User', 'responder_user_id', 'id');
    }


    public function merchantLinkRelation()
    {
        return $this->hasMany('App\Models\MerchantLinkRelation',"merchantlink_id","id");
    }


}
