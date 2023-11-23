<?php

namespace App\Modules\Weather\Repositories;

use function env;

/**
 * @deprecated
 *
 * todo или прменить или удалить врямя восхода и захода
 */
class TemperModel
{
    /**
     * врямя восхода и захода
     *
     * @param $day Дата для расчета
     * @return array
     */
    public static function getSunriseAndDusk($day)
    {
        $sunrise = [];
        $dusk = [];
        for ($i = $day; $i > -1; $i--) {
            $date = date(strtotime("-" . $i . " days"));
            $sun_info = date_sun_info($date, env('SENSOR_LATITUDE', ''), env('SENSOR_LOGITUDE', ''));
            $sunrise[] = ['start' => date("Y-m-d H:i:i", $sun_info['sunrise']), 'stop' => date("Y-m-d H:i:i", $sun_info['sunset'])];
            if ($sun_info['astronomical_twilight_begin'] === true) {
//                $dusk[]=['stop'=>date("Y-m-d H:i:i", $sun_info['sunrise']),  'start' =>date("Y-m-d H:i:i", $sun_info['sunset'])];
//                $dusk[]=['start'=>date("Y-m-d H:i:i", $sun_info['sunrise']),  'stop' =>date("Y-m-d H:i:i", $sun_info['sunset'])];
            } else {
                $dusk[] = ['start' => date("Y-m-d H:i:i", $sun_info['astronomical_twilight_begin']), 'stop' => date("Y-m-d H:i:i", $sun_info['sunrise'])];
                $dusk[] = ['start' => date("Y-m-d H:i:i", $sun_info['sunset']), 'stop' => date("Y-m-d H:i:i", $sun_info['astronomical_twilight_end'])];
            }
        }
        return compact('sunrise', 'dusk');
    }

}
