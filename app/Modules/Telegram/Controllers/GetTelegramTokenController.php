<?php

namespace App\Modules\Telegram\Controllers;

use App\Http\Controllers\MyController;

class GetTelegramTokenController extends MyController
{
    public function __construct()
    {
        $this->middleware('role:superAdmin');
    }


    public function __invoke()
    {
//        $opt = GetGlobalOption::getOneValue('telegram_token', '');
//        $status
//        if ($opt != '') {
//            $opt = '*';
//        }
//        return response(['status' => $opt);
    }

}
