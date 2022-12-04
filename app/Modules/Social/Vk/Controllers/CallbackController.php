<?php

namespace App\Modules\Social\Vk\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Socials\LinkedSocialAccounts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CallbackController extends Controller
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


    public function redirect($profile=false){
        $url  = env('APP_URL',false);
        if ($profile) {
            return redirect($url.'/#/user/profile');
        }
        return redirect($url);
    }

    public function index(Request $request)
    {
        $code = $request->get('code');
        if ($code){
            $token = $this->getUser($code);
            $user_soc = LinkedSocialAccounts::where('provider_id', $token['user_id'])->where('provider_name', 'vkontakte')->first();
            if ($user_soc){
                Auth::guard()->login($user_soc->user, true);
                return $this->redirect();
            } else {
                // соц.зер не найден
                if (Auth::guest()) {
                    if (isset($token['email'])) {
                        $user = User::where('email', $token['email'])->first();
                        if (!$user) {
                            $user = $this->createUser($token);
                        }
                    } else {
                        $date = date_create();
                        $token['email'] = 'no_email_'.date_timestamp_get($date).'@example.com';
                        $user = $this->createUser($token);
                    }
                } else {
                    $user = Auth::user();
                }
                if ($user){
                    $user_soc = $this->createSocUser($user->id, $token['user_id'], $token['email']);
                    if ($user_soc){
                        Auth::guard()->login($user, true);
                        Session::flash('message', 'Пожалуйста. Уточните свои данные!!');
                        return $this->redirect(true);
                    }
                }


            }
        }
        return 'no code';
    }

    public function getUser($code)
    {
        $config = config('services.vkontakte');
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/api/vk/auth/callback";

        $params = array(
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'code' => $code,
            'redirect_uri' => $actual_link,
        );
        $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);
        return $token;
    }

    /**
     * создаем пользователя с данными из соцсети
     * @param $token
     */
    public function createUser($token){
        $params = array(
            'uids' => $token['user_id'],
            'fields' => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big,status,phone',
            'access_token' => $token['access_token'],
            'v' => '5.101'
        );
        $response = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
        $userInfo = $response['response'][0];
        $user = new User();
        $user->name = $userInfo['first_name'];
        $user->email = $token['email'];
        $user->email_verified_at = \Carbon\Carbon::now();
        $user->last_name = $userInfo['last_name'];
        $user->avatar = $userInfo['photo_big'];
        $user->password = '';
        if ($user->save()){
            return $user;
        }
        return false;
    }

    /**
     * создать линк на соц акуант
     *
     * @param $user_id
     * @param $provider_id
     * @return LinkedSocialAccounts|bool
     */
    public function createSocUser($user_id, $provider_id, $email= null){
        $user_soc= new LinkedSocialAccounts();
        $user_soc->user_id = $user_id;
        $user_soc->email = $email;
        $user_soc->provider_name = 'vkontakte';
        $user_soc->provider_id = $provider_id;
        if ($user_soc->save()){
            return $user_soc;
        }
        return false;
    }
}
