<?php

namespace App\Modules\Telegram\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Setting\Actions\GetGlobalOption;
use App\Modules\Telegram\Classes\TelegramClass;
use Illuminate\Http\Request;

class GetUserInfoController extends Controller
{

    public function __construct()
    {
//        $this->middleware('role:superAdmin');
    }


    public function __invoke(Request $request)
    {
        $id = 967840621;
        $file_path = '';
        $contentf = '';
        $file_id = '';

        $token = GetGlobalOption::getOneValue('telegram_token', '');

        $telegram = new TelegramClass();
        $res = $telegram->sendRaw('getUserProfilePhotos', ['user_id' => $id]);
//        $res = $telegram->sendRaw('getMyName', ['chat_id' => 6419047478]);

        $content = json_decode($res->getBody()->getContents());
////        dd($content->result);
        $file_id = $content->result->photos[0][2]->file_id;
        $resf = $telegram->sendRaw('getFile', ['file_id' => $file_id]);
        $contentf = json_decode($resf->getBody()->getContents());
        $file_path = $contentf->result->file_path;
        return response([
            $file_path,
            $contentf,
            $file_id,
            $content,
            "https://api.telegram.org/file/bot$token/$file_path"
        ]);
    }


}
