<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\MyController;
use App\Modules\User\Repositories\GetPermissionsForUserRepository;
use Illuminate\Support\Facades\Auth;


class GetMyInfoController extends MyController
{

    public function __invoke()
    {
        $user = Auth::user();
        $data = [
            'user' => $user->toArray(),
            'permissions' => array_merge(['user', 'owner'], (new GetPermissionsForUserRepository($user))->toArray()),
        ];
        return response($data);
    }

}
