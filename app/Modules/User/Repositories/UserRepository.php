<?php

namespace App\Modules\User\Repositories;


use App\Modules\User\Models\UserModel;

class UserRepository
{

    private $query;

    public function __construct()
    {
        $this->query = UserModel::query();
    }

    public function byEmail($email): UserRepository
    {
        $this->query->where('email', $email);
        return $this;
    }


    public function find($find)
    {
        if ($find) {
            $this->query->where(function ($query) use ($find) {
                $query->where('name', 'like', '%' . $find . '%')
                    ->orWhere('middle_name', 'like', '%' . $find . '%')
                    ->orWhere('last_name', 'like', '%' . $find . '%')
                    ->orWhere('email', 'like', '%' . $find . '%');
            });
        }
        return $this;
    }

    /**
     * @return UserModel|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     * @throws \Exception
     */
    public function run()
    {
        $model = $this->query->first();
        if ($model) {
            return $model;
        }
        throw new \Exception('Пользователь не найден');
    }


    public function paginate($limit)
    {
        return $this->query->paginate($limit);
    }


}