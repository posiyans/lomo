<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\MyController;
use App\Modules\Auth\Resources\AuthUserResource;
use Illuminate\Support\Facades\Auth;


class GetMyInfoController extends MyController
{

    public function __invoke()
    {
//        return response('', 403);
        $user = Auth::user();
        return response(new AuthUserResource($user));
    }

}
