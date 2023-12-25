<?php

namespace App\Modules\Owner\Actions;

use App\Modules\Owner\Models\OwnerUserModel;
use App\Modules\User\Actions\ChangeRoleToUserAction;

/**
 * Удалить пользователя  от собственника
 */
class DeleteUserFromOwnerAction
{
    private $owner;


    public function __construct(OwnerUserModel $owner)
    {
        $this->owner = $owner;
    }

    public function run()
    {
        $user = $this->owner->user;
        $this->owner->user_uid = null;
        if ($this->owner->logAndSave('Удаления пользователя из собственника')) {
            (new ChangeRoleToUserAction($user))->deleteRole('owner');
            return true;
        }
        throw new \Exception('Ошибка изменения отношения собственника и пользователя');
    }

}
