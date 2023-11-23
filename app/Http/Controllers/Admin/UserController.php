<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Modules\User\Repositories\GetUserByUidRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,user-show,user-edit');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->ability('superAdmin', ['user-show', 'user-edit'])) {
            $find = strtolower($request->get('find', false));
            $query = User::query();
            if ($find) {
                $query->whereRaw("(lower(concat_ws('',name,last_name,middle_name)) like '%" . $find . "%')");
            }
            $models = $query->paginate($request->limit);
            return UserResource::collection($models);
        }
        return [];
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
        if ($user->ability('superAdmin', ['user-show', 'user-edit'])) {
            if ($model = (new GetUserByUidRepository($id))->run()) {
                return new UserResource($model);
            }
        }
        return false;
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
        if ($user->ability('superAdmin', 'edit-users')) {
            $userModel = User::find($id);
            $data = $request->all();
            if ($userModel && $data && $data['id'] == $id) {
                unset($data['password']);
                $userModel->fill($data);
                $userModel->save();
//                if (isset($data['steads'])) {
//                    $userModel->syncSteads($data['steads']);
//                }
                // todo убрать это отсюда!
                if (isset($data['roles']['roles'])) {
                    $userModel->syncRoles($data['roles']['roles']);
                }
                if (isset($data['roles']['permissions'])) {
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


    public function sendVerifyMailToken($id)
    {
        if ($user = User::find($id)) {
            if (!$user->email_verified_at) {
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
