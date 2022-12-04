<?php

namespace App\Modules\Social\Vk\Controllers;

use App\Http\Controllers\Controller;


class GetPathAuthController extends Controller
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


    public static function index(){
        $value = config('services.vkontakte');
//        dd($value);
        $url = 'http://oauth.vk.com/authorize';
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/api/vk/auth/callback";
        $params = array(
            'client_id'     => $value['client_id'],
            'redirect_uri'  => $actual_link,
            'response_type' => 'code',
            'display'=> 'page',
            'scope' => 'email,status,wall'
        );
        $vk_url =$url . '?' . urldecode(http_build_query($params));
        return $vk_url;
    }

}
