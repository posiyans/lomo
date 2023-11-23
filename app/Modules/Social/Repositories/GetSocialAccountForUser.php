<?php

namespace App\Modules\Social\Repositories;

use App\Modules\Social\Models\LinkedSocialAccounts;
use App\Modules\User\Models\UserModel;

/**
 * Получить отношение к соц сетям для пользователя
 */
class GetSocialAccountForUser
{
    private $query;

    public function __construct(UserModel $user)
    {
        $this->query = LinkedSocialAccounts::where('user_id', $user->id);
    }

    public function social($name)
    {
        $model = $this->query->where('provider_name', $name)->first();
        if ($model) {
            return $model;
        }
        throw new \Exception('Модель не найдена');
    }

    public function all()
    {
        return $this->query->get();
    }


}