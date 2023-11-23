<?php

namespace App\Modules\Setting\Actions;

use App\Modules\Setting\Models\GlobalOptionModel;

class GetGlobalOption
{

    public static function getOneValue($name, $default = null)
    {
        try {
            $item = self::getOne($name);
            return $item->value;
        } catch (\Exception $e) {
            return $default;
        }
    }

    public static function getOne($name)
    {
        $item = GlobalOptionModel::where('name', $name)->first();
        if ($item) {
            return $item;
        }
        throw new \Exception('Значение не найдено');
    }
}
