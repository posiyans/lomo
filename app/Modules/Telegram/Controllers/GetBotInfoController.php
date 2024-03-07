<?php

namespace App\Modules\Telegram\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Setting\Actions\GetGlobalOption;
use App\Modules\Telegram\Classes\TelegramMyInfo;
use Illuminate\Http\Request;

class GetBotInfoController extends Controller
{

    public function __invoke(Request $request)
    {
        $bot = (new TelegramMyInfo())->run();
        if ($bot) {
            $bot->two_factor_telegram = GetGlobalOption::getOneValue('two_factor_telegram_enable', false);
            return response(['status' => true, 'data' => $bot]);
        }
        return response(['status' => false]);
    }


}
