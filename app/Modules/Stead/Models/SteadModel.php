<?php

namespace App\Modules\Stead\Models;

use App\Models\MyModel;
use App\Modules\File\Models\FileModel;
use App\Modules\MeteringDevice\Models\MeteringDeviceModel;
use App\Modules\Owner\Models\OwnerUserModel;
use App\Modules\Stead\Factories\SteadModelFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;


/**
 * Модель участков
 *
 * @property int $id
 * @property int $gardening_id
 * @property string $number
 * @property int|null $user_id
 * @property string|null $surname
 * @property string|null $name
 * @property string|null $patronymic
 * @property string|null $size
 * @property string|null $email
 * @property string|null $history
 * @property array|null $descriptions
 * @property array|null $options опции для участка
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @method static \Illuminate\Database\Eloquent\Builder|SteadModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SteadModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SteadModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|SteadModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SteadModel whereDescriptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SteadModel whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SteadModel whereGardeningId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SteadModel whereHistory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SteadModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SteadModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SteadModel whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SteadModel whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SteadModel wherePatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SteadModel whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SteadModel whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SteadModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SteadModel whereUserId($value)
 * @mixin \Eloquent
 */
class SteadModel extends MyModel
{
    use HasFactory;

    protected $fillable = ['number', 'size'];

    protected $options_fillable = [
        'kadastr',
        'coordinates',
        'address'
    ];


    protected $casts = [
        'options' => 'array',
        'id' => 'integer'
    ];

    protected static function newFactory(): Factory
    {
        return SteadModelFactory::new();
    }

    public function owners()
    {
        return $this->belongsToMany(
            OwnerUserModel::class,
            'owner_user_model_stead_model',
            'stead_id',
            'owner_uid',
            'id',
            'uid'
        )
            ->withPivot('proportion');
    }


    public function metering_devices()
    {
        return $this->hasMany(MeteringDeviceModel::class, 'stead_id', 'id');
    }

    public function files()
    {
        return $this->morphMany(FileModel::class, 'commentable', null, 'commentable_uid', 'id');
    }


}
