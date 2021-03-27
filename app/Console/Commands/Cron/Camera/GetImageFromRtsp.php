<?php
namespace App\Console\Commands\Cron\Camera;

use App\Models\Options\GlobalOptionModel;

class GetImageFromRtsp
{
    public static function start()
    {
        $optionName = 'siteCameraSetting';
        $camera = GlobalOptionModel::getOptionList($optionName);
        foreach ($camera  as $item) {
            print_r($item->value);
            echo PHP_EOL;
        }
    }


}
