<?php

namespace App\Modules\User\Repositories;


use App\Modules\User\Models\UserModel;

class GetUserByUidRepository
{

    private $uid;

    public function __construct($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return UserModel|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     * @throws \Exception
     */
    public function run()
    {
        $model = UserModel::where('id', $this->uid)->orWhere('uid', $this->uid)->first();
        if ($model) {
            return $model;
        }
        throw new \Exception('Пользователь не найден');
    }


}