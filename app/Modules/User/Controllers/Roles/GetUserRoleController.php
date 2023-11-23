<?php

namespace App\Modules\User\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Modules\User\Repositories\GetUserByUidRepository;
use Illuminate\Http\Request;

use function response;

/**
 * Получить роли и разрешения о пользователя
 *
 */
class GetUserRoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,user-show,user-edit');
    }

    public function __invoke(Request $request)
    {
        try {
            $user = (new GetUserByUidRepository($request->user_uid))->run();
            $roles = [];
            foreach ($user->roles as $role) {
                $roles[] = [
                    'name' => $role->name,
                    'display_name' => $role->display_name
                ];
            }

            $permissions = [];
            foreach ($user->permissions as $permission) {
                $permissions[] = [
                    'name' => $permission->name,
                    'display_name' => $permission->display_name
                ];
            }
            return ['roles' => $roles, 'permissions' => $permissions];
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 410);
        }
    }


}
