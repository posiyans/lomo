<?php

namespace App\Modules\Rate\Actions;

use App\Modules\Rate\Models\RateGroupModel;

/**
 * Обновить группу тарифов
 */
class UpdateRateGroupAction
{
    protected $rate_group;

    public function __construct(RateGroupModel $rate_group, array $options = [])
    {
        $this->rate_group = $rate_group;
        $this->rate_group->fill($options);
    }


    public function run()
    {
        if ($this->rate_group->logAndSave('Изменние группы тарифов')) {
            return $this->rate_group;
        }
        throw new \Exception('Ошибка изменнения группы тарифов');
    }


}
