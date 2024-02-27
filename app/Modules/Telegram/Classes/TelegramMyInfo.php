<?php

namespace App\Modules\Telegram\Classes;

class TelegramMyInfo
{
    public function run()
    {
        try {
            $telegram = new TelegramClass();
            $res = $telegram->sendRaw('getMe', []);
            $content = json_decode($res->getBody()->getContents());
            if ($content->ok) {
                return $content->result;
            }
            return false;
        } catch (\Exception $e) {
        }
        return false;
    }
}
