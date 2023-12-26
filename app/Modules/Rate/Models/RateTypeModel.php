<?php

namespace App\Modules\Rate\Models;

use App\Models\MyModel;

/**
 * Модель едииницы тарифа (день, ночь, мусор, взнос)
 *
 * @property int $id
 * @property int $device_id
 * @property string $ratio_a
 * @property string $ratio_b
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $date_start дата начала действия тарифа
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @method static \Illuminate\Database\Eloquent\Builder|RateModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RateModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RateModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|RateModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RateModel whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RateModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RateModel whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RateModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RateModel whereRatioA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RateModel whereRatioB($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RateModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RateTypeModel extends MyModel
{
    protected $casts = [
        'enable' => 'boolean',
    ];
}
