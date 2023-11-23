<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use function response;

class LogoutController extends Controller
{

    public function __invoke(Request $request)
    {
        Auth::guard('web')->logout();
        Session::remove('user_uid');
        return response()->json(['status' => true], 200);
    }
}
