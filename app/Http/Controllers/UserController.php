<?php

namespace App\Http\Controllers;

use App\Models\AppealModel;
use App\Models\Log;
use App\Models\Stead;
use App\Models\Temper\TemperModel;
use App\Models\Laratrust\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Socialite;
use function foo\func;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('vue', ['template' => '<user-profile></user-profile>']);
    }

    public function info()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $temp = ['user'];
            if ($user->hasRole('superAdmin')) {
//                $perms = Permission::all();
                $perms = Permission::where('name', 'not like', 'ban-%')->get();
            } else {
                $perms = $user->allPermissions();
            }

            foreach ($perms as $perm) {
                $temp[] = $perm->name;
            }


//            $roles = [];
//            foreach ($user->roles as $role){
//                $roles[] = $role->name;
//            }
//            $permissions = [];
//            foreach ($user->permissions as $permission){
//                $permissions[] = $permission->name;
//            }
            $user->permissions;
            $user->roles;
            $user->socialList = $user->getSocialList();
            $user->allPermissions = $temp;
            if (Session::has('message')){
                $user->message = Session::get('message');
            }


            return json_encode($user);
        } else {
            return json_encode(['allPermissions'=>['guest']]);
        }
    }

    public function save(Request $request)
    {
        $data = $request->input();
        $user = Auth::user();
//        $user_clone = clone $user;
        if ($data['user']['id'] == $user->id){
            if ($data['user']['email'] != $user->email)
            {
                $user->changeEmail($data['user']['email']);
            }
            $user->last_name = $data['user']['last_name'];
            $user->name = $data['user']['name'];
            $user->middle_name = $data['user']['middle_name'];
            $user->phone = $data['user']['phone'];
            $user->avatar = $data['user']['avatar'];
            $user->adres = $data['user']['adres'];
            $user->consent_personal = $data['user']['consent_personal'];
            $user->consent_to_email = $data['user']['consent_to_email'];
            $text = '';
            if (is_array($data['user']['steads_id'])) {
                sort($data['user']['steads_id']);

                foreach ($data['user']['steads_id'] as $item) {
                    $stead = Stead::find($item);
                    if ($stead && $stead->user_id != $user->id) {
                        $text .= 'Добавь ' . $item;
                        $appeal = AppealModel::where('type', $user->id . '_stead_' . $stead->number)
                            ->where('status', 'open')
                            ->first();
                        if (!$appeal) {
                            $appeal = new AppealModel();
                            $appeal->title = 'Запрос на потверждение собственности участка № ' . $stead->number;
                            $appeal->type = $user->id . '_stead_' . $stead->number;
                            $appeal->save();
                        }
                    }
                }
            }
             $user->steads_id = $data['user']['steads_id'];
//            if (isset($data['user']['steads']) && count($data['user']['steads']) == 1){
//                $user->steads_id = $data['user']['steads'][0];
//            }
           $user->logAndSave('Изменение персональных данных пользователя');
            // todo Log::saveDiff($user, $user_clone, 'Изменение персональных данных пользователя');
            return json_encode(['status'=>true, 'data'=>$data, [$user, $text]]);
        }
        return json_encode([$data['user']['id'], $user->id]);
    }
}
