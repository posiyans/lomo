<?php

namespace App\Modules\User\Controllers\Auth;

use App\Http\Controllers\MyController;
use App\Models\AppealModel;
use App\Models\Log;
use App\Models\Stead;
use App\Models\Temper\TemperModel;
use App\Models\Laratrust\Permission;
use App\Modules\User\Repositories\GetPermissionsForUserRepository;
use App\Modules\User\Repositories\GetUidForUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Socialite;
use function foo\func;

class GetMyInfoController extends MyController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $data = [
                'user' => [
                    'uid' => (new GetUidForUserRepository($user))->run(),
                    'email' => $user->email,
                    'name' => $user->name,
                    'last_name' => $user->last_name,
                    'middle_name' => $user->middle_name,
                ]
            ];
            $data['permissions'] = (new GetPermissionsForUserRepository($user))->toArray();
            return json_encode($data);
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
