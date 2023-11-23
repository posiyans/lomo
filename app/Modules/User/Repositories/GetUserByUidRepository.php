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

    public function run()
    {
        if (is_integer($this->uid)) {
            $model = UserModel::where('id', $this->uid)->first();
        }
        if (is_string($this->uid)) {
            $model = UserModel::where('uid', $this->uid)->first();
        }
        if ($model) {
            return $model;
        }
        throw new \Exception('Пользователь не найден');
    }


}