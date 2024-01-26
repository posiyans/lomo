<?php

namespace App\Modules\Camera\Repositories;

use App\Modules\Camera\Actions\SaveImageFromCameraAction;
use App\Modules\Setting\Actions\GetGlobalOption;
use Illuminate\Support\Facades\Cache;

class CameraRepository
{
    private static $optionName = 'siteCameraSetting';
    static protected $cache_prefix = 'Camera_image_id_';


    public static function getById($id)
    {
        $item = GetGlobalOption::findOneByValueField(self::$optionName, 'id', $id);
        if ($item) {
            return $item;
        }
        throw new \Exception('Камера не найдена');
    }


    public static function getObjectById($id)
    {
        $item = self::getById($id);
        return self::getObject($item->value, true);
    }

    public static function all($fullFields = false)
    {
        $items = GetGlobalOption::getAll(self::$optionName);
        $data = [];
        foreach ($items as $item) {
            $data[] = self::getObject($item->value, $fullFields);
        }
        return $data;
    }


    private static function getObject($item, $full = false)
    {
        $data = new \stdClass();
        $data->id = $item['id'];
        $data->name = $item['name'];
        if ($full) {
            $data->url = $item['url'] ?? '';
            $data->access = $item['access'] ?? false;
            $data->ttl = $item['ttl'] ?? 1800;
        }
        return $data;
    }

    public static function getImagePath($id)
    {
        $model = CameraRepository::getObjectById($id);
        $cacheName = CameraRepository::getCacheName($id);
        return Cache::tags('Camera')->remember($cacheName, $model->ttl, function () use ($model) {
            return (new SaveImageFromCameraAction($model))->run();
        });
    }


    public static function getCacheName($id)
    {
        return static::$cache_prefix . $id;
    }


    public static function getOptionName()
    {
        return self::$optionName;
    }

}