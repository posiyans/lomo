<?php

namespace App\Modules\Rate\Actions;

use App\Modules\Rate\Models\RateTypeModel;

/**
 * Обновить описание тариф
 */
class UpdateRateTypeAction
{
    protected $rate_type;

    public function __construct(RateTypeModel $rate_type, array $options = [])
    {
        $this->rate_type = $rate_type;
        $this->rate_type->fill($options);
    }


    public function run()
    {
        if ($this->rate_type->logAndSave('Изменние описания тарифа')) {
            return $this->rate_type;
        }
        throw new \Exception('Ошибка изменнения тарифа');
    }


}
