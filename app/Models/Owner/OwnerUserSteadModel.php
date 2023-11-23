<?php

namespace App\Models\Owner;

use App\Models\MyModel;
use App\Models\Stead;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Owner\OwnerUserSteadModel
 *
 * @property int $id
 * @property string $owner_uid uid собственника
 * @property int $stead_id id участка
 * @property int $proportion Доля собственности
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @property-read \App\Models\Owner\OwnerUserModel|null $owner
 * @property-read Stead|null $stead
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserSteadModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserSteadModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserSteadModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserSteadModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserSteadModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserSteadModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserSteadModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserSteadModel whereOwnerUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserSteadModel whereProportion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserSteadModel whereSteadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserSteadModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserSteadModel withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserSteadModel withoutTrashed()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @mixin \Eloquent
 */
class OwnerUserSteadModel extends MyModel
{
    use HasFactory, SoftDeletes;

    public function ownerFullName()
    {
        return $this->owner->fullName();
    }
    public function nameForMyRole()
    {
        return $this->owner->nameForMyRole();
    }


    public function owner()
    {
        return $this->hasOne(OwnerUserModel::class, 'uid', 'owner_uid');
    }

    public function stead()
    {
        return $this->hasOne(Stead::class, 'id', 'stead_id');
    }
}
