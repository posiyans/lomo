<?php

namespace App\Modules\Owner\Models;

use App\Models\MyModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Owner\OwnerUserValueModel
 *
 * @property int $id
 * @property string $uid uid
 * @property string $property свойство
 * @property mixed|null $value значение
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserValueModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserValueModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserValueModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserValueModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserValueModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserValueModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserValueModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserValueModel whereProperty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserValueModel whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserValueModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserValueModel whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserValueModel withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserValueModel withoutTrashed()
 * @mixin \Eloquent
 */
class OwnerUserValueModel extends MyModel
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'value' => 'encrypted',
    ];
    protected $fillable = ['uid', 'property'];
}
