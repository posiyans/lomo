<?php

namespace App\Modules\Rate\Models;

use App\Models\MyModel;

/**
 * Модель едииницы тарифа (день, ночь, мусор, Целевой взнос, Членский взнос)
 **/
class RateTypeModel extends MyModel
{
    protected $casts = [
        'enable' => 'boolean',
    ];

    protected $fillable = ['name', 'description'];

    public function rate_group()
    {
        return $this->belongsTo(RateGroupModel::class, 'rate_group_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @deprecated
     *
     */
    public function rateGroup()
    {
        return $this->rate_group();
    }

    public function currentRate()
    {
        return $this->hasOne(RateModel::class, 'rate_type_id', 'id')
            ->where('date_start', '<', date('Y-m-d'))
            ->orderByDesc('created_at');
    }

}
