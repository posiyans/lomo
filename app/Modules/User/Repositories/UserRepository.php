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


}