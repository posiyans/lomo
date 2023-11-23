<?php

namespace App\Modules\Avatar\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\File\Classes\SaveFileForObjectClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Загрузить аватар пользвателя
 *
 */
class UploadUserAvatarController extends Controller
{

    public function index(Request $request)
    {
        try {
            $user = Auth::user();
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
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 450);
        }
    }


}
