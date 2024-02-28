<?php

namespace App\Modules\Owner\Actions;

use App\Modules\Owner\Models\OwnerUserModel;
use App\Modules\User\Actions\ChangeRoleToUserAction;
use App\Modules\User\Models\UserModel;

/**
 * Добавить пользователя собственнику
 */
class CompareOwnerAndUserAction
{
    private $owner;
    private $user;

    public function __construct(OwnerUserModel $owner, UserModel $user)
    {
        $this->owner = $owner;
        $this->user = $user;
    }

    public function run()
    {
        if ($this->user->owner()->save($this->owner)) {
            (new ChangeRoleToUserAction($this->user))->addRole('owner');
            return true;
        }
        throw new \Exception('Ошибка изменения отношения собственника и пользователя');
    }

}
