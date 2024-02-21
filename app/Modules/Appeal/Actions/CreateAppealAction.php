<?php

namespace App\Modules\Appeal\Actions;

use App\Modules\Appeal\Models\AppealModel;

/**
 *
 * создать обращение
 *
 */
class CreateAppealAction
{
    private $model;

    public function __construct($data)
    {
        $this->model = AppealModel::create($data);
        $this->model->status = 'open';
    }


    public function run()
    {
        if ($this->model->logAndSave('Создание обращения')) {
            return $this->model;
        }
        throw new \Exception('Ошибка создания обращения');
    }
}
