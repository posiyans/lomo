<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Repositories\GetUserByUidRepository;
use App\Modules\User\Resources\AdminUserAllInfoResource;
use App\Modules\User\Validators\UpdateUserInfoValidator;

use function response;

/**
 * Обновить информацию о пользователе
 *
 */
class UpdateUserInfoController extends Controller
{

    public function __invoke(UpdateUserInfoValidator $request)
    {
        try {
            $user = (new GetUserByUidRepository($request->user_uid))->run();
            $user->fill($request->validated())->save();
            return new AdminUserAllInfoResource($user);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 410);
        }
    }


}
