<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Onewaylocation extends Model
{
    protected $fillable = ["oneway_id","id","location_id","deleted_at"];
    protected $table = 'onewaylocation';
}
