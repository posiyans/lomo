<?php

namespace App\Modules\Telegram\Controllers;

use App\Http\Controllers\MyController;
use App\Modules\Setting\Actions\GetGlobalOption;

class GetTelegramTokenController extends MyController
{
    public function __construct()
    {
        $this->middleware('role:superAdmin');
    }


    public function __invoke()
    {
        $opt = GetGlobalOption::getOneValue('telegram_token', '');
        return response($opt);
    }

}
