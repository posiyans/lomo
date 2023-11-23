<?php

namespace App\Modules\File\Repositories;

/***
 *
 * Сопоставление типа модели и Модали при загруке файла
 */
class GetObjectByType
{
    private $object;

    public function __construct($type, $uid)
    {
        $class = (new GetModelNameByType($type))->run();
        $this->object = $class::where('uid', $uid)->first();
    }

    public function run()
    {
        if ($this->object) {
            return $this->object;
        }
        throw new \Exception('Модель не найдена');
    }
}