<?php

namespace App\Modules\User\Actions;

use App\Models\Laratrust\Role;
use App\Modules\User\Models\UserModel;

/**
 * Изменить роль пользователю
 *
 */
class ChangeRoleToUserAction
{

    private $user;

    public function __construct(UserModel $user)
    {
        $this->user = $user;
    }

    /**
     * Добавить роль
     *
     * @param $role_name
     * @return bool
     */
    public function addRole($role_name)
    {
        $role = Role::where('name', $role_name)->first();
        $this->user->attachRole($role);
        return true;
    }

    /**
     * Удалить роль
     *
     * @param $role_name
     * @return bool
     */
    public function deleteRole($role_name)
    {
        $role = Role::where('name', $role_name)->first();
        $this->user->detachRole($role);
        return true;
    }


}
