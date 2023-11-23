<?php

namespace App\Modules\BanUser\Classes;

use App\Modules\User\Models\UserModel;

/**
 * Добавить бан пользователю
 */
class IsBanUserAdminClass
{


    public static function isAdmin(UserModel $user)
    {
        return $user->ability('superAdmin', ['comment-ban', 'user-ban-edit', 'user-ban-show']);
    }


}