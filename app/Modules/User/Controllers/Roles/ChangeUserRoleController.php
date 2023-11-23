<?php

namespace App\Modules\User\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Models\Laratrust\Role;
use App\Modules\User\Repositories\GetUserByUidRepository;
use Auth;
use Illuminate\Http\Request;

use function response;

/**
 * Изменить роль у пользователя
 *
 */
class ChangeUserRoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,role-edit');
    }

    public function __invoke(Request $request)
    {
        try {
            $my_user = Auth::user();
            $user_uid = $request->user_uid;
            $role_name = $request->role_name;
            $action = $request->action; // add or delete
            if (!$my_user->hasRole($role_name)) {
                throw new \Exception('Ошибка доступа');
            }
            $user = (new GetUserByUidRepository($user_uid))->run();
            $role = Role::where('name', $role_name)->first();
            if ($action == 'add') {
                $user->attachRole($role);
            } else {
                $user->detachRole($role);
            }
            return ['data' => ['role' => $role_name, 'action' => $action]];
//            return ['roles' => $roles, 'permissions' => $permissions];
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 410);
        }
    }


}
