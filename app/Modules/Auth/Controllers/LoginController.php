<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Actions\TwoFactor\SendOrCheckTwoFactorCodeClass;
use App\Modules\Auth\Resources\AuthUserResource;
use App\Modules\Log\Models\LogModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function username()
    {
        return 'email';
    }

    public function login(Request $request)
    {
        $email = trim($request->email);
        $password = $request->password;
        if (empty($email)) {
            return throw new \Exception('Email не может быть пустым');
        }
        $credentials = ['email' => $email, 'password' => $password];

        if (Auth::guard()->once($credentials)) {
            // togo добавить логирование входа по паролю + тоже самое через соц сети
            $user = Auth::user();
            try {
                return (new SendOrCheckTwoFactorCodeClass($user))->code($request->code)->run();
            } catch (\Exception $e) {
            }
            Session::put('user_uid', $user->uid);
            Auth::login($user);
            $result = [
                'status' => 'done',
                'data' => new AuthUserResource($user)
            ];
            return response($result);
        }
        $log = new LogModel();
        $log->description = 'bad login or password';
        $log->value = $credentials;
        $log->type = 'alert';
        $log->save();
        return response(['status' => 'errorCode', 'error' => 'Не верный логин или пароль']);
    }

    public function logout(Request $request)
    {
        return 'sdfsdfs';
//        Auth::guard('web')->logout();
//        return response()->json('', 204);
//        $this->guard()->logout();
//        $request->session()->invalidate();
//        return $request;
    }
}
