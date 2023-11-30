<?php

namespace App\Modules\Owner\Models;

use App\Models\MyModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

/**
 * App\Models\Owner\OwnerUserModel
 *
 * @property int $id
 * @property string $uid uid
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Owner\OwnerUserSteadModel> $steads
 * @property-read int|null $steads_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Owner\OwnerUserValueModel> $values
 * @property-read int|null $values_count
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserModel whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserModel withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OwnerUserModel withoutTrashed()
 * @mixin \Eloquent
 */
class OwnerUserModel extends MyModel
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'uid';
    protected $keyType = 'string';

    public function fullName()
    {
        return $this->getValue('surname', '') . ' ' . $this->getValue('name', '') . ' ' . $this->getValue('middle_name', '');
    }

    public function smallName()
    {
        $str = $this->getValue('surname', '');
        if (strlen($this->getValue('name', '')) > 0) {
            $str .= ' ' . mb_substr($this->getValue('name', ''), 0, 1) . '.';
        }
        if (strlen($this->getValue('middle_name', '')) > 0) {
            $str .= mb_substr($this->getValue('middle_name', ''), 0, 1) . '.';
        }
        return $str;
    }

    public function nameForMyRole()
    {
        if (\Auth::user()->ability('superAdmin', 'owner-show')) {
            return $this->fullName();
        } else {
            return $this->smallName();
        }
    }

    /**
     * отношения со свойствами
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function values()
    {
        return $this->hasMany(OwnerUserValueModel::class, 'uid', 'uid');
    }

    /**
     * вернуть значение свойства
     *
     * @param $name
     * @return false
     */
    public function getValue($property, $default = false)
    {
        $cacheName = $this->getCacheName($property);

        $data = Cache::tags(['owner_user_field_value'])->remember($cacheName, 6000, function () use ($property) {
            $model = OwnerUserValueModel::where('property', $property)->where('uid', $this->uid)->first();
            if ($model) {
                return $model->value;
            }
            return false;
        });
        if ($data) {
            return $data;
        }
        return $default;
    }

    /**
     * Установить занчения поля
     *
     * @param $property String имя
     * @param $value String значение
     * @return bool
     */
    public function setValue($property, $value)
    {
        $model = OwnerUserValueModel::firstOrCreate(['uid' => $this->uid, 'property' => $property]);
        $model->value = $value;
        if ($model->logAndSave('Измение поля')) {
            $cacheName = $this->getCacheName($property);
            Cache::tags(['owner_user_field_value'])->forget($cacheName);
            return $model;
        }
        throw new \Exception('Ошибка добавления поля');
    }

    /**
     * удалить поле
     *
     * @param $property String имя поля
     * @return $this
     */
    public function delValue($property)
    {
        $models = OwnerUserValueModel::where('property', $property)->where('uid', $this->uid)->get();
        foreach ($models as $model) {
            $model->delete();
        }
        return $this;
    }


    /**
     * удалить все поля
     *
     * @return $this
     */
    public function delAllValue()
    {
        foreach ($this->fields as $key => $field) {
            $this > $this->delValue($key);
        }
        return $this;
    }

    public function steads()
    {
        return $this->hasMany(OwnerUserSteadModel::class, 'owner_uid', 'uid');
    }


    private function getCacheName($name)
    {
        return 'ownerUser_' . $this->uid . '_Field_' . $name;
    }
}
