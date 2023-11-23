<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


/**
 * App\Modules\User\Models\UserUidModel
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @property-read \App\Modules\User\Models\UserModel|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserUidModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserUidModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserUidModel query()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @mixin \Eloquent
 */
class UserUidModel extends \App\Models\MyModel
{
    use HasFactory;
//    protected $casts = [
//        'user_id' => 'encrypted',
//    ];

    public function user() {
        return $this->hasOne('App\Modules\User\Models\UserModel', 'id', 'user_id');
    }
}
