<?php

namespace App\Modules\BanUser\Repositories;

use App\Modules\BanUser\Models\BanUserModel;
use App\Modules\User\Models\UserModel;
use Illuminate\Database\Eloquent\Builder;

class BanUserRepository
{

    private $query;

    public function __construct()
    {
        $this->query = BanUserModel::query();
    }

    public function byUid(string $uid)
    {
        $this->query->where('uid', $uid);
        return $this;
    }

    public function forClassUid(string $uid)
    {
        $this->query->where('commentable_uid', $uid);
        return $this;
    }

    /**
     * бан для конкретного класса
     *
     * @param $class
     * @return $this
     */
    public function forClass($class)
    {
        $this->query->where('commentable_type', $class);
        return $this;
    }

    public function forUser(UserModel $user)
    {
        $this->query->where('user_id', $user->id);
        return $this;
    }

    public function sortBy($column, $direction)
    {
//        if (array_key_exists($column, BanUserModel::attributes)) {
        $this->query->orderBy($column, $direction);
//        }
        return $this;
    }

    /**
     * Показать с удалеными
     *
     * @return $this
     */
    public function withTrashed()
    {
        $this->query->withTrashed();
        return $this;
    }

    public function run()
    {
        return $this->query->get();
    }

    public function paginate($limit)
    {
        return $this->query->paginate($limit);
    }

    public function findUser($find)
    {
        $this->query->whereHas('user', function (Builder $query) use ($find) {
            $query->where('name', 'like', '%' . $find . '%')
                ->orWhere('last_name', 'like', '%' . $find . '%')
                ->orWhere('middle_name', 'like', '%' . $find . '%')
                ->orWhere('email', 'like', '%' . $find . '%');
        });
        return $this;
    }

    public function one()
    {
        $model = $this->query->first();
        if ($model) {
            return $model;
        }
        throw new \Exception('Бан не найден');
    }

    public function noBan()
    {
        $model = $this->query->first();
        if ($model) {
            return throw new \Exception('Бан');
        }
        return true;
    }
}