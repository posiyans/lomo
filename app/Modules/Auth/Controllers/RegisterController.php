<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Laratrust\Role;
use App\Modules\User\Models\UserModel;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use function event;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    'unique:App\Modules\User\Models\UserModel'
                ],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ],
            [
                'email.unique' => 'Пользватель с данным e-mail уже есть в системе',
                'email.required' => 'Не указан e-mail',
                'name.required' => 'Не указано имя',
                'password.required' => 'Не указан пароль',
                'password.confirmed' => 'Пароли не совпадают',
            ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return UserModel
     */
    protected function create(array $data): UserModel
    {
        return UserModel::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'uid' => Str::uuid(),
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);
//        Session::put('user_uid', $user->uid);
        $this->fistUser($user);
        return response($user);
    }

    protected function guard()
    {
        return Auth::guard();
    }


    protected function fistUser(UserModel $user)
    {
        if ($user->id == 1) {
            $role = Role::where('name', 'superAdmin')->first();
            if ($role) {
                $user->syncRolesWithoutDetaching([$role->id]);
            }
        }
    }
}
