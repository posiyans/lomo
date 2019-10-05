<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;

class HomeController extends Controller
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
        $temper = TemperController::getTemper();
        return view('welcome', compact('temper'));
    }

    /**
     *
     *
     *
     */
    public function vk()
    {
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
        echo $link = '<p><a href="' . $url . '?' . urldecode(http_build_query($params)) . '">Аутентификация через ВКонтакте</a></p>';


        //return Socialite::with('vkontakte')->scopes(['status'])->redirect();
    }

    public function vkcalback()
    {
        // $clientId = "";
        // $clientSecret = ";
        // $redirectUrl = "";
        // $additionalProviderConfig = ['site' => 'lomo.loc'];
        // $config = new \SocialiteProviders\Manager\Config($clientId, $clientSecret, $redirectUrl, $additionalProviderConfig);
        // return Socialite::with('vkontakte')->setConfig($config)->redirect();


        // dd(Socialite::driver('vkontakte')->user());
        // dd($user);
        //return Socialite::with('vkontakte')->redirect();
        $value = config('services.vkontakte');
        if (isset($_GET['code'])) {
            $params = array(
                'client_id' => $value['client_id'],
                'client_secret' => $value['client_secret'],
                'code' => $_GET['code'],
                'redirect_uri' => $value['redirect'],
            );
        }

	    $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);
        dump($token);
        if (isset($token['access_token'])) {
            $params = array(
                'uids'         => $token['user_id'],
                'fields'       => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big,status',
                'access_token' => $token['access_token'],
                'v' => '5.101'
            );

            $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
            dump($userInfo);
        }
    }
}
