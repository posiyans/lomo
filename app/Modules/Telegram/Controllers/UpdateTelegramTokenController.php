<?php

namespace App\Modules\Telegram\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Setting\Actions\SetGlobalOption;
use Illuminate\Http\Request;

class UpdateTelegramTokenController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:superAdmin');
    }

    public function __invoke(Request $request)
    {
        try {
            $token = $request->token;
            SetGlobalOption::setOneValue('telegram_token', $token);
            return response(['status' => true]);
        } catch (\Exception $e) {
        }
        return response(['errors' => 'Ошибка обновления токена'], 450);
    }

}
