<?php

namespace App\Http\Controllers;

use App\LinkedSocialAccounts;
use App\Models\Temper\TemperModel;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Socialite;

class VkController extends Controller
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


//    /**
//     *
//     *
//     *
//     */
//    public function vk()
//    {
//        $value = config('services.vkontakte');
//        //dd($value);
//        $url = 'http://oauth.vk.com/authorize';
//
//        $params = array(
//            'client_id'     => $value['client_id'],
//            'redirect_uri'  => $value['redirect'],
//            'response_type' => 'code',
//            'display'=> 'page',
//            'scope' => 'email,status,wall'
//        );
//        echo $link = '<p><a href="' . $url . '?' . urldecode(http_build_query($params)) . '">Аутентификация через ВКонтакте</a></p>';
//
//
//        //return Socialite::with('vkontakte')->scopes(['status'])->redirect();
//    }

    public static function getUrl(){
        $value = config('services.vkontakte');
        //dd($value);
        $url = 'http://oauth.vk.com/authorize';

        $params = array(
            'client_id'     => $value['client_id'],
            'redirect_uri'  => $value['redirect'],
            'response_type' => 'code',
            'display'=> 'page',
            'scope' => 'email,status,wall'
        );
        $vk_url =$url . '?' . urldecode(http_build_query($params));
        return $vk_url;
    }

    public function vkcalback(Request $request)
    {
        $code = $request->get('code');
        if ($code){
            $token = $this->getToken($code);
            $user_soc = LinkedSocialAccounts::where('provider_id', $token['user_id'])->where('provider_name', 'vkontakte')->first();
            if ($user_soc){
                Auth::guard()->login($user_soc->user, true);
                return $this->redirect();
            } else {
                $user = User::where('email', $token['email'])->first();
                if (!$user) {
                    $user = $this->createUser($token);
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
//        return false;
//        $value = config('services.vkontakte');
//        if (isset($_GET['code'])) {
//            $params = array(
//                'client_id' => $value['client_id'],
//                'client_secret' => $value['client_secret'],
//                'code' => $_GET['code'],
//                'redirect_uri' => $value['redirect'],
//            );
//            $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);
//            $user_soc = LinkedSocialAccounts::where('provider_id', $token['user_id'])->where('provider_name', 'vkontakte')->first();
//            if ($user_soc) {
//                Auth::guard()->login($user_soc->user);
//                return $this->redirect();
////                return '<html>close!!!<script>window.history.back(-2);</script></html>';
////                return '<html>close!!!<script>window.open("","_parent",""); window.close();</script></html>';
////                return redirect('/')->withCookie(cookie('Admin-Token', 'admin-token', 360,null, null, false, false, true));
////                return redirect('http://lomo.loc:9527/');
//            } else {
//                $user = User::where('email', $token['email'])->first();
//                if (!$user && isset($token['access_token'])) {
//
//                    $params = array(
//                        'uids' => $token['user_id'],
//                        'fields' => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big,status,phone',
//                        'access_token' => $token['access_token'],
//                        'v' => '5.101'
//                    );
//                    $response = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
//                    $userInfo = $response['response'][0];
//                    $user = new User();
//                    $user->name = $userInfo['first_name'];
//                    $user->email = $token['email'];
//                    $user->email_verified_at = \Carbon\Carbon::now();
//                    $user->last_name = $userInfo['last_name'];
//                    $user->avatar = $userInfo['photo_big'];
//                    $user->password = '';
//                    $user->save();
//                }
//
//                $user_soc= new LinkedSocialAccounts();
//                $user_soc->user_id = $user->id;
//                $user_soc->provider_name = 'vkontakte';
//                $user_soc->provider_id = $token['user_id'];
//                $user_soc->save();
//                Auth::guard()->login($user);
////                return '<html>close!!!<script>window.history.back(-2);</script></html>';
////                return 'close';
//                return redirect('/');
//            }
//
//        }

        return 'no code';


    }

    public function getToken($code)
    {
        $config = config('services.vkontakte');
        $params = array(
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'code' => $code,
            'redirect_uri' => $config['redirect'],
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
