<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\MyController;
use App\Modules\User\Repositories\GetPermissionsForUserRepository;
use App\Modules\User\Resources\UserInfoResource;
use Illuminate\Support\Facades\Auth;


class GetMyInfoController extends MyController
{

    public function __invoke()
    {
        //todo запихать это в ресурс
        $user = Auth::user();
        $roles = $user->roles->map(function ($role) {
            return $role->name;
        });
        $data = [
            'user' => new UserInfoResource($user),
            'permissions' => array_merge(['user'], (new GetPermissionsForUserRepository($user))->toArray(), $roles->toArray()),
            'roles' => $roles,
        ];

        return response($data);
    }

}
