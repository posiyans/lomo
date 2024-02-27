<?php

namespace App\Modules\Telegram\Controllers;

use App\Http\Controllers\MyController;
use App\Modules\Setting\Actions\SetGlobalOption;
use Illuminate\Http\Request;

class ChangeTwoFactorEnableController extends MyController
{
    public function __construct()
    {
        $this->middleware('role:superAdmin');
    }


    public function __invoke(Request $request)
    {
        $value = $request->value;
        SetGlobalOption::setOneValue('two_factor_telegram_enable', $value);
        return response(['status' => true]);
    }

}
