<?php

namespace App\Modules\Rate\Actions;

use App\Modules\Rate\Models\RateModel;
use App\Modules\Rate\Models\RateTypeModel;

/**
 * Обновить стоимость тарифа
 */
class UpdateRateAction
{
    protected $rate;

    public function __construct(RateTypeModel $rate_type, array $options = [])
    {
        $this->rate = new RateModel();
        $this->rate->fill($options);
        $this->rate->rate_type_id = $rate_type->id;
    }


    public function run()
    {
        if ($this->rate->logAndSave('Добавление нового тарифа')) {
            return $this->rate;
        }
        throw new \Exception('Ошибка добавления тарифа');
    }


}
