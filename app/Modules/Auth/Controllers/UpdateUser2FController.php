<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\MyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UpdateUser2FController extends MyController
{

    public function __invoke(Request $request)
    {
        $user = Auth::user();
        $opt = $user->options;
        $opt['two_factor'] = $request->two_factor ?? $opt['two_factor'] ?? [];
        $opt['two_factor_enable'] = $request->two_factor_enable ?? $opt['two_factor_enable'] ?? [];
        $user->options = $opt;
        $user->save();
        //todo add log
        return response(true);
    }

}
