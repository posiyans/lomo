<?php

namespace App\Modules\Stead\Actions;

use App\Modules\Stead\Models\SteadModel;


class CreateSteadAction
{

    private $model;

    public function __construct($number)
    {
        $this->model = new SteadModel();
        $this->model->number = $number;
    }

    public function size($size)
    {
        if ($size != null) {
            $this->model->size = $size;
        }
        return $this;
    }

    public function run()
    {
        if ($this->model->logAndSave('Добавление участка')) {
            return $this->model;
        }
        return throw new \Exception('Ошибка создания участка');
    }

}
