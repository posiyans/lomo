<?php

namespace App\Models\Receipt;

use App\Models\MyModel;

/**
 * Модель тарифоф для приборов учета
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
 * @method static \Illuminate\Database\Eloquent\Builder|Rate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rate query()
 * @method static \Illuminate\Database\Eloquent\Builder|Rate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rate whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rate whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rate whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rate whereRatioA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rate whereRatioB($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rate whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @mixin \Eloquent
 */
class Rate extends MyModel
{
    //
    // Модель тарифоф
}
