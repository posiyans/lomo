<?php

namespace App\Modules\Social\vendor\Vk\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Social\vendor\Vk\Repositories\GetUserByVkCode;
use Auth;
use Illuminate\Http\Request;

use function redirect;
use function session;

class CallbackController extends Controller
{

    public function __invoke(Request $request)
    {
        $code = $request->get('code');
        if ($code) {
            $user = (new GetUserByVkCode($code))->run();
            Auth::guard()->login($user, true);
            return redirect(session('vkReferer'));
        }
    }
}
