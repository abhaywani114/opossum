<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarPark extends Model
{
    protected $guarded = ['id'];
    protected $table = "carparklot";

    public function carparkoper()
    {
        return $this->hasOne('App\Models\CarparkOper', 'carparklot_id', 'id');
    }

    public function evreceiptcarpark()
    {
        return $this->hasMany('App\Models\EvreceiptCarpark', 'carparklot_id', 'id');
    }



    public static function formatMoney($number, $cents = 1)
    { // cents: 0=never, 1=if needed, 2=always
        if (is_numeric($number)) { // a number
            if (!$number) { // zero
                $money = ($cents == 2 ? '0.00' : '0'); // output zero
            } else { // value
                if (floor($number) == $number) { // whole number
                    $money = number_format($number, ($cents == 2 ? 2 : 0)); // format
                } else { // cents
                    $money = number_format(round($number, 2), ($cents == 0 ? 0 : 2)); // format
                } // integer or decimal
            } // value
            return '' . $money;
        } // numeric
    }

    public static function customFormatMoney($number){
        $last = "";
        $rest_number = "";
        $rest_number = substr($number, 0, -2);
        $rest_number = self::formatMoney($rest_number);
        if (strlen($number)==1)
        {
            $last = "0".$number;
            $rest_number = "0";
        }else{
            if (strlen($number)==2)
            {
                $last = $number;
                $rest_number = "0";

            }else{
                $last = strval($number)[strlen($number)-2]."".strval($number)[strlen($number)-1];
            }
        }


        return $rest_number.".".$last;
    }
}
