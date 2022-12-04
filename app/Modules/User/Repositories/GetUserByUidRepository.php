<?php
namespace App\Modules\User\Repositories;


use App\Modules\User\Models\UserModel;
use App\Modules\User\Models\UserUidModel;

class GetUserByUidRepository {

    private $uid;

    public function __construct($uid)
    {
        $this->uid = $uid;
    }

    public function run()
    {
        if (is_integer($this->uid)) {
            $model = UserUidModel::where('id', $this->uid)->first();
        }
        if (is_string($this->uid)) {
            $model = UserUidModel::where('uid', $this->uid)->first();
        }
        if ($model) {
            return $model->user;
        }
        throw new \Exception('Пользователь не найден');
    }


}