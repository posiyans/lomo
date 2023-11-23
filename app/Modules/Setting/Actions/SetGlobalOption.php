<?php

namespace App\Modules\Setting\Actions;

use App\Modules\Setting\Models\GlobalOptionModel;

class SetGlobalOption
{

    public static function setOneValue($name, $value)
    {
        try {
            $item = GetGlobalOption::getOne($name);
        } catch (\Exception $e) {
            $item = new GlobalOptionModel();
            $item->name = $name;
            $item->type = 'array';
        }
        $item->value = $value;
        $item->logAndSave('Изменение значения');
        return $value;
    }
}
