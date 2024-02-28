<?php

namespace App\Modules\Telegram\Controllers;

use App\Http\Controllers\Controller;
use App\Notifications\TelegramNotification;
use Illuminate\Http\Request;
use NotificationChannels\Telegram\TelegramUpdates;

class GetLastMessageUserIdController extends Controller
{

    public function __construct()
    {
//        $this->middleware('only_moderator');
    }


    public function __invoke(Request $request)
    {
        $updates = TelegramUpdates::create()
            ->latest()
            ->options([
                'timeout' => 0,
            ])
            ->get();
        if ($updates['ok']) {
            if (isset($updates['result'][0])) {
                $userTelegram = $updates['result'][0]['message']['from'];
                $line = "Ваш id:" . $userTelegram['id'];
                \Notification::send($userTelegram['id'], new TelegramNotification($line));
                return ['status' => true, 'data' => $userTelegram];
            } else {
                return ['status' => false, 'error' => 'Нет сообщений'];
            }
        }
    }


}
