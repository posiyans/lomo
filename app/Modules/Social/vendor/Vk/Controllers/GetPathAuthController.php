<?php

namespace App\Modules\Social\vendor\Vk\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Social\Repositories\GetSocialAccountForUser;
use Auth;
use Hash;
use Illuminate\Http\Request;

use function config;
use function response;
use function session;

class GetPathAuthController extends Controller
{

    public function __invoke(Request $request)
    {
        if (Auth::user()) {
            try {
                if ((new GetSocialAccountForUser(Auth::user()))->social('vkontakte')) {
                    return response(['errors' => 'Пользователь уже авторизован в сети'], 450);
                }
            } catch (\Exception $e) {
            }
            $password = $request->password;
            if (!Hash::check($password, Auth::user()->password)) {
                return response(['errors' => 'Неверны пароль пользователя'], 450);
            }
        }
        session(['vkReferer' => $request->server('HTTP_REFERER')]); // чтоб занать где находится фронтенд
        $value = config('services.vkontakte');
        $url = 'http://oauth.vk.com/authorize';
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/api/v2/social/vk/auth/callback";
        $params = array(
            'client_id' => $value['client_id'],
            'redirect_uri' => $actual_link,
            'response_type' => 'code',
            'display' => 'page',
            'scope' => 'email,phone,phone_number'
        );
        $vk_url = $url . '?' . urldecode(http_build_query($params));
        return $vk_url;
    }

}
