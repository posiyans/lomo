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

    public static function getAll($name)
    {
        return GlobalOptionModel::where('name', $name)->get();
    }


    public static function findOneByValueField($name, $field, $val)
    {
        try {
            $item = GlobalOptionModel::where('name', $name)->whereJsonContains('value->' . $field, $val)->first();
        } catch (\Exception $e) {
            $items = GlobalOptionModel::where('name', $name)->get();
            foreach ($items as $options) {
                if ($options->value[$field] == $val) {
                    $item = $options;
                }
            }
        }
        if ($item) {
            return $item;
        }
        throw new \Exception('Значение не найдено');
    }

}
