<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\User\Resources\AdminUserAllInfoResource;
use Illuminate\Http\Request;

use function response;

/**
 * Получить информацию о пользователе
 *
 */
class GetUserListController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,user-show,user-edit');
    }

    public function __invoke(Request $request)
    {
        try {
            $users = (new UserRepository())->find(mb_strtolower($request->find))->paginate($request->limit);
            return AdminUserAllInfoResource::collection($users);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 410);
        }
    }


}
