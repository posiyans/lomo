<?php

namespace App\Modules\Avatar\Actions;

use App\Http\Controllers\Controller;
use App\Modules\File\Classes\SaveFileForObjectClass;
use App\Modules\User\Repositories\GetUserByUidRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Сохранить аватар для модели
 *
 */
class SaveAvataForObjectController extends Controller
{

    public function index(Request $request)
    {
//        try {
        $uid = $request->uid;
        $user = (new GetUserByUidRepository($uid))->run();
        if ($user->id === Auth::user()->id) {
            $inputFile = $request->file('avatar');
            $file = (new SaveFileForObjectClass($user))
                ->name($inputFile->getClientOriginalName())
                ->size($inputFile->getSize())
                ->description('avatar')
                ->type($inputFile->getMimeType())
                ->file($inputFile)
                ->run();
            $data = [
                "files" => [
                    "file" => '/api/v2/avatar/user/get?uid=' . $request->uid,
                    'id' => $file->uid,
                ]
            ];
            return response($data);
        }
//        } catch (\Exception $e) {
//            return response()->download(__DIR__ . '/img.png', 'avatar.png');
//        }
    }


}
