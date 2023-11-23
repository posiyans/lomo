<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;

/**
 *Повторно отправить пользователю письмо для верификации email
 */
class SendMailVerifyCodeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('throttle:1,1200');
    }

    public function __invoke()
    {
        $user = \Auth::user();
        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }
        return response('');
    }
}
