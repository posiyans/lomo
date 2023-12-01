<?php

namespace App\Modules\Stead\Models;

use App\Models\MyModel;


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

//    protected $fillable = ['number', 'descriptions'];
    protected $casts = [
        'options' => 'array',
    ];

//    protected $table = 'stead_models';


}
