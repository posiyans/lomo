<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Repositories\GetUserByUidRepository;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;

/**
 *Повторно отправить пользователю письмо для верификации email
 */
class SendMailVerifyCodeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('throttle:5,1200');
    }

    public function __invoke(Request $request)
    {
        $user = \Auth::user();
        if ($request->uid && $user->ability('superAdmin', ['user-show', 'user-edit'])) {
            $user = (new GetUserByUidRepository($request->uid))->run();
        }
        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }
        return response('');
    }
}
