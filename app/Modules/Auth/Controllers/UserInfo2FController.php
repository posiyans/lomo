<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\MyController;
use App\Modules\Setting\Actions\GetGlobalOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserInfo2FController extends MyController
{

    public function __invoke(Request $request)
    {
        $user = Auth::user();
        $data = [
            'id' => $user->id,
            'two_factor' => $user->options['two_factor'] ?? false,
            'two_factor_enable' => $user->options['two_factor_enable'] ?? [],

            'two_factor_valid' => [
                [
                    'key' => 'google2fa',
                    'label' => 'Google Authenticator',
                    'error' => isset($user->options['twofa_secret']) ? false : true,
                    'error_message' => 'Не утановлен SecretKey',
                ]
            ]
        ];
        if (GetGlobalOption::getOneValue('two_factor_telegram_enable', false)) {
            $data['two_factor_valid'][] = [
                'key' => 'telegram',
                'label' => 'Telegram ',
                'error' => !isset($user->options['telegram']) && !empty($user->options['telegram']),
                'error_message' => 'Не указан id в Telegram',
            ];
        }
//        if ((new SettingRepository())->getOptionValue('two_factor_mail_enable', false)) {
//            $data['two_factor_valid'][] = [
//                'key' => 'mail',
//                'label' => 'E-mail',
//                'error' => !$user->email,
//                'error_message' => 'Не указан адрес почты',
//            ];
//        }
        return response($data);
    }

}
