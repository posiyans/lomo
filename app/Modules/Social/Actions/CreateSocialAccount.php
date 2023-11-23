<?php

namespace App\Modules\Social\Actions;

use App\Modules\Social\Models\LinkedSocialAccounts;
use App\Modules\Social\Repositories\GetSocialAccountForUser;
use App\Modules\User\Models\UserModel;


class CreateSocialAccount
{
    private $model;

    public function __construct(UserModel $user, $social_type, $social_id)
    {
        try {
            $this->model = (new GetSocialAccountForUser($user))->social($social_type);
        } catch (\Exception $e) {
            $this->model = new LinkedSocialAccounts();
            $this->model->user_id = $user->id;
            $this->model->provider_name = $social_type;
        }
        $this->model->provider_id = $social_id;
    }

    public function run(): LinkedSocialAccounts
    {
        $this->model->save();
        if ($this->model->save()) {
            return $this->model;
        }
        throw new \Exception('Ошибка создания пользователя');
    }


}