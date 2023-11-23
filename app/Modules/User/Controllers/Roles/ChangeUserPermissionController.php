<?php

namespace App\Modules\User\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Models\Laratrust\Permission;
use App\Modules\User\Repositories\GetUserByUidRepository;
use Auth;
use Illuminate\Http\Request;

use function response;

/**
 * Изменить разрешение у пользователя
 *
 */
class ChangeUserPermissionController extends Controller
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
            $permission_name = $request->permission_name;
            $action = $request->action; // add or delete
            if (!$my_user->ability('superAdmin', $permission_name)) {
                throw new \Exception('Ошибка доступа');
            }
            $user = (new GetUserByUidRepository($user_uid))->run();
            $permission = Permission::where('name', $permission_name)->first();
            if (!$permission) {
                throw new \Exception('Разрешение не найдено');
            }
            if ($action == 'add') {
                $user->attachPermission($permission);
            } else {
                $user->detachPermission($permission);
            }
            return ['data' => ['permission' => $permission, 'action' => $action]];
//            return ['roles' => $roles, 'permissions' => $permissions];
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 410);
        }
    }


}
