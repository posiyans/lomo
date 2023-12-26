<?php

namespace App\Modules\Rate\Actions;

use App\Modules\Rate\Models\RateGroupModel;

/**
 * Создать Группу тарифов
 */
class CreateRateTypeAction
{
    protected $rate_group;

    public function __construct($name)
    {
        $this->rate_group = new RateGroupModel();
        $this->rate_group->name = $name;
    }


    public function run()
    {
        if ($this->rate_group->logAndSave('Создание группы тарифов')) {
            return $this->rate_group;
        }
        throw new \Exception('Ошибка создания группы');
    }


}
