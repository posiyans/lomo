<?php

namespace App\Modules\Telegram\Classes;


use App\Modules\User\Models\UserModel;

class TelegramDeleteLoginCodeMessage
{
    private $user;

    public function __construct(UserModel $user)
    {
        $this->user = $user;
    }

    public function run()
    {
        $telegram_login_message_id = $this->user->getField('telegram_login_message_id', false);
        $chat_id = $this->user->getField('telegram', false);

        if ($telegram_login_message_id && $chat_id) {
            try {
                $telegram = new TelegramClass();
                $d = [
                    'chat_id' => $chat_id,
                    'message_id' => $telegram_login_message_id,
                ];
//            dd($d);
                $telegram->deleteMessage($d);
            } catch (\Exception $e) {
            }
            $this->user->setField('telegram_login_message_id', false);
        }
    }


}
