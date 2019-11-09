<?php

namespace App\Models\Temper;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель тарифоф для приборов учета
 */
class TemperModel extends Model
{
    //
    // Модель температуры
    protected $connection = 'mysql_temper';
    protected $table = 'chch_data';
    public $timestamps = false;



    public static function getTemper($week = false){
        if($week){
            return self::getDataSensor(false, 7);
        }
        return self::getDataSensor();
    }


    public static function getDataSensor($adres=false, $day=false){
        if(!$adres){
            $adres = env('PRIMARY_SENSOR','');
        }
        if($day){
            $data = TemperModel::where('adres', $adres)->where('time', '>', (new \DateTime())->modify('-'.$day.' day')->format('Y-m-d'))->orderBy('time', 'ASC')->get();

        }else{
            $data = TemperModel::where('adres', $adres)->orderBy('time', 'desc')->limit(1)->first();
            $data->temp=round(floatval($data->temp),1);
        }
        return $data;
    }

    public static function getSunriseAndDusk($day){
        $sunrise=[];
        $dusk=[];
        for($i=$day;$i>-1;$i--){
            $date= date(strtotime("-".$i." days"));
            $sun_info = date_sun_info($date, env('SENSOR_LATITUDE',''), env('SENSOR_LOGITUDE',''));
            $sunrise[]=['start'=>date("Y-m-d H:i:i", $sun_info['sunrise']), 'stop' =>date("Y-m-d H:i:i", $sun_info['sunset'])];
            $dusk[]=['start'=>date("Y-m-d H:i:i", $sun_info['astronomical_twilight_begin']), 'stop' =>date("Y-m-d H:i:i", $sun_info['sunrise'])];
            $dusk[]=['start'=>date("Y-m-d H:i:i", $sun_info['sunset']), 'stop' =>date("Y-m-d H:i:i", $sun_info['astronomical_twilight_end'])];
        }
        return compact('sunrise', 'dusk');
    }

}
