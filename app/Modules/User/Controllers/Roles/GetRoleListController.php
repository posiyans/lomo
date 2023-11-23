<?php

namespace App\Modules\User\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Models\Laratrust\Permission;
use App\Models\Laratrust\Role;
use Illuminate\Http\Request;

/**
 * Получить список ролей и разрешений
 *
 */
class GetRoleListController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,user-edit,user-show,access-admin-panel');
    }

    public function __invoke(Request $request)
    {
        $roles = Role::all();
        $role_data = [];
        foreach ($roles as $role) {
            $role_data[] = [
                'name' => $role->name,
                'display_name' => $role->display_name
            ];
//            $role->permissions;
        }
        $permissions = Permission::all();
        $permissions_array = [];
        foreach ($permissions as $permission) {
            $permissions_array[] = [
                'name' => $permission->name,
                'display_name' => $permission->display_name
            ];
        }
        return ['roles' => $role_data, 'permissions' => $permissions_array];
    }


}
