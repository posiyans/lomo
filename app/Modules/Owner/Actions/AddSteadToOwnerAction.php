<?php

namespace App\Modules\Owner\Actions;

use App\Modules\Owner\Models\OwnerUserModel;
use App\Modules\Owner\Models\OwnerUserSteadModel;
use App\Modules\Stead\Models\SteadModel;

/**
 * Добавить участок собственнику
 */
class AddSteadToOwnerAction
{
    private $model;

    public function __construct(SteadModel $stead, OwnerUserModel $owner)
    {
        $this->model = OwnerUserSteadModel::firstOrCreate(['stead_id' => $stead->id, 'owner_uid' => $owner->uid]);
        if (!$this->model->proportion) {
            $this->model->proportion = 100;
        }
    }

    public function proportion($val)
    {
        $this->model->proportion = $val;
        return $this;
    }

    public function run()
    {
        if ($this->model->logAndSave('Изменение собственности')) {
            return $this->model;
        }
        throw new \Exception('Ошибка изменения собственности');
    }


}
