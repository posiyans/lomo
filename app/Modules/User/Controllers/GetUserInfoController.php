<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Repositories\GetUserByUidRepository;
use App\Modules\User\Resources\AdminUserAllInfoResource;
use Illuminate\Http\Request;

use function response;

/**
 * Получить информацию о пользователе
 *
 */
class GetUserInfoController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,user-show,user-edit');
    }

    public function __invoke(Request $request)
    {
        try {
            $user_uid = $request->user_uid ?? $request->uid;
            $user = (new GetUserByUidRepository($user_uid))->run();
            return new AdminUserAllInfoResource($user);
//            return ['user' => $user];
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 410);
        }
    }


}
