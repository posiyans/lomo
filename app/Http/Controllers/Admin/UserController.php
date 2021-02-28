<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\AppealResource;
use App\Http\Resources\ConrtollerResource;
use App\Http\Resources\SteadResource;
use App\Http\Resources\UserResource;
use App\Models\AppealModel;
use App\Models\Stead;
use App\Models\Laratrust\Permission;
use App\Models\Laratrust\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,show-users');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->ability('superAdmin', 'show-users')) {
            $query = User::query();
            $models = $query->paginate($request->limit);
            return UserResource::collection($models);
        }
        return [];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        if ($user->ability('superAdmin', 'show-users')) {
            if ($model = User::find($id)){
                return new UserResource($model);
            }

        }
        return false;

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->ability('superAdmin', 'edit-users')){
            $userModel = User::find($id);
            $data = $request->user;
            if ($userModel && $data && $data['id'] == $id){
                unset($data['password']);
                $userModel->fill($data);
                $userModel->save();
                if (isset($data['steads'])){
                    $userModel->syncSteads($data['steads']);
                }
                if (isset($data['roles']['roles'])){
                    $userModel->syncRoles($data['roles']['roles']);
                }
                if (isset($data['roles']['permissions'])){
                    $userModel->syncPermissions($data['roles']['permissions']);
                }
                return $userModel;
            }
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function roleList(){
        $roles = Role::all();
        $permissions = Permission::all();
        return ['roles'=>$roles, 'permissions'=>$permissions];
    }


    public function sendVerifyMailToken($id)
    {
        if ($user = User::find($id)){
            if (!$user->email_verified_at){
                $user->email_verified_at = null;
                $user->save();
                $user->sendEmailVerificationNotification();
                return json_encode(['status' => true, 'data' => 'Письмо отправлено']);
            }
            return json_encode(['status' => false, 'data' => 'Почта уже потверждена']);
        }
        return json_encode(['status' => false, 'data' => 'Пользователь не найден']);
    }
}
