<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Onewayrelation extends Model
{
    protected $fillable = ["oneway_id","id","default_location_id","ptype","status"];
    protected $table = 'onewayrelation';
    public function oneway()
    {
        return $this->belongsTo('App\Models\Oneway', 'oneway_id', 'id');
    }
}
