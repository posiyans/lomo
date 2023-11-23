<?php

namespace App\Console\Commands\Cron\Camera;

use App\Modules\Setting\Models\GlobalOptionModel;

class GetImageFromRtsp
{
    public static function start()
    {
        $optionName = 'siteCameraSetting';
        $camera = GlobalOptionModel::getOptionList($optionName);
        foreach ($camera as $item) {
            print_r($item->value);
            echo PHP_EOL;
        }
    }


}
