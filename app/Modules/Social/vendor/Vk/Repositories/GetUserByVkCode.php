<?php

namespace App\Modules\Social\vendor\Vk\Repositories;

use App\Modules\File\Classes\SaveFileForObjectClass;
use App\Modules\Social\Actions\CreateSocialAccount;
use App\Modules\Social\Repositories\GetSocialAccountBySocialData;
use App\Modules\Social\vendor\Vk\Actions\ChangeUserForSocialAccount;
use App\Modules\User\Models\UserModel;
use App\Modules\User\Repositories\UserRepository;
use Auth;
use Http;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;

use function config;

class GetUserByVkCode
{

    private $vk_code;
    private $user_token;
    private $user_id;
    private $user_email;
    private $soc_name = 'vkontakte';

    public function __construct($vk_code)
    {
        $this->vk_code = $vk_code;
    }

    public function run()
    {
        $this->getUserAccessToken();
        return $this->getSocialUser();
    }

    /**
     * получить токен доступа и id пользователя
     *
     * @return array|mixed
     * @throws \Exception
     */
    private function getUserAccessToken()
    {
        $config = config('services.vkontakte');
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/api/v2/social/vk/auth/callback";
        $params = array(
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'code' => $this->vk_code,
            'redirect_uri' => $actual_link,
        );
        $response = Http::get('https://oauth.vk.com/access_token', $params);
        if ($response->successful()) {
            $data = $response->json();
            $this->user_id = $data['user_id'];
            $this->user_token = $data['access_token'];
            $this->user_email = $data['email'];
            return $data;
        }
        throw new \Exception('Неверный код доступа');
    }

    private function getSocialUser()
    {
        try {
            $soc_user = (new GetSocialAccountBySocialData($this->soc_name, $this->user_id))->run();
            if (Auth::user() && Auth::user()->id != $soc_user->user_id) {
                (new ChangeUserForSocialAccount($soc_user, Auth::user()))->run();
            }
        } catch (\Exception $e) {
            $soc_user = $this->createSocialUser();
        }
        return $soc_user->user;
    }


    private function getLocalUser()
    {
        if (\Auth::user()) {
            return Auth::user();
        } else {
            try {
                $user = (new UserRepository())->byEmail($this->user_email)->run(); // ищем в системе с такимже email
            } catch (\Exception $e) {
                $user = $this->createLocalUser(); // создаем нового пользователя
            }
            return $user;
        }
    }

    /**
     * @throws \Exception
     */
    private function createSocialUser()
    {
        $local_user = $this->getLocalUser();
        $social_user = (new CreateSocialAccount($local_user, $this->soc_name, $this->user_id))->run();
        return $social_user;
    }

    private function createLocalUser()
    {
        $data = $this->getVkUserInfo();
        $date = date_create();
        $email = $this->user_email ?: 'no_email_' . date_timestamp_get($date) . '@example.com';
        $user = UserModel::create([
            'name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $email,
            'password' => '',
            'uid' => Str::uuid(),
        ]);
        $user->save();
        $this->saveAvatar($user, $data['photo_big']);
        event(new Registered($user));
        return $user;
    }


    private function saveAvatar($user, $avatar_url)
    {
        $response = Http::get($avatar_url);
        $tmpfname = tempnam(sys_get_temp_dir(), "user_avatar");
        $handle = fopen($tmpfname, "w");
        fwrite($handle, $response->body());
        fclose($handle);
        (new SaveFileForObjectClass($user))
            ->name('user_avatar_' . $user->id)
            ->size(filesize($tmpfname))
            ->description('avatar')
            ->type('image/jpeg')
            ->file($tmpfname)
            ->uploadUser($user)
            ->run();
    }

    private function getVkUserInfo()
    {
        $params = array(
            'uids' => $this->user_id,
            'fields' => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big,phone,nickname',
            'access_token' => $this->user_token,
            'v' => '5.101'
        );
        $response = Http::get('https://api.vk.com/method/users.get', $params);
        if ($response->successful()) {
            return $response->json()['response'][0];
        }
    }

}