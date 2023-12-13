<?php

namespace App\Modules\Stead\Actions;

use App\Modules\Stead\Models\SteadModel;
use App\Modules\Stead\Repositories\SteadFiledRepository;


class UpdateSteadFieldAction
{

    private $model;

    public function __construct(SteadModel $stead)
    {
        $this->model = $stead;
    }

    public function field($field_name, $value)
    {
        if (SteadFiledRepository::isOptional($field_name)) {
            $options = $this->model->options;
            $options[$field_name] = $value;
            $this->model->options = $options;
        } else {
            $this->model->$field_name = $value;
        }
        return $this;
    }

    public function run()
    {
        if ($this->model->logAndSave('Изменение данных участка')) {
            return $this->model;
        }
        return throw new \Exception('Ошибка изменение данных участка');
    }

}
