<?php
namespace App\Modules\User\Repositories;


use App\Modules\User\Classes\CreateUidForUser;
use App\Modules\User\Models\UserModel;
use App\Modules\User\Models\UserUidModel;

class GetUidForUserRepository {

    private $user;

    public function __construct(UserModel $user)
    {
        $this->user = $user;
    }

    public function run()
    {

        $model = UserUidModel::where('user_id', $this->user->id)->first();
        if ($model) {
            return $model->uid;
        }
        return (new CreateUidForUser($this->user))->run();
//        throw new \Exception('Uid не найден');
    }


}