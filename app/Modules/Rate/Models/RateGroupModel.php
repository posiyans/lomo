<?php

namespace App\Modules\Rate\Models;

use App\Models\MyModel;

/**
 * Модель группыы тарифов (электроэнергия, взносы)
 *
 **/
class RateGroupModel extends MyModel
{

    //
    // Модель типов тарифов
    //типы квитанций
    //depends рассчитывать
    // 0 - фиксированная сумма (наверно)
    // 1 - расчитывается от плащади участка
    // 2 - расчитывается по показания

    protected $casts = [
        'id' => 'integer',
        'depends' => 'integer',
        'options' => 'array',
        'auto_invoice' => 'boolean'
    ];

    protected $fillable = [
        'auto_invoice',
        'depends',
        'name',
        'options',
        'payment_period',
    ];

    public function rateType()
    {
        return $this->hasMany(RateTypeModel::class, 'rate_group_id', 'id');
    }


}
