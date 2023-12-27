<?php

namespace App\Modules\Rate\Models;

use App\Models\MyModel;

/**
 * Модель едииницы тарифа (день, ночь, мусор, взнос)
 **/
class RateTypeModel extends MyModel
{
    protected $casts = [
        'enable' => 'boolean',
    ];

    protected $fillable = ['name', 'description'];

    public function rateGroup()
    {
        return $this->belongsTo(RateGroupModel::class, 'rate_group_id', 'id');
    }

    public function currentRate()
    {
        return $this->hasOne(RateModel::class, 'rate_type_id', 'id')
            ->where('date_start', '<', date('Y-m-d'))
            ->orderByDesc('created_at');
    }

}
