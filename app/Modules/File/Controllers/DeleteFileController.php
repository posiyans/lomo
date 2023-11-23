<?php

namespace App\Modules\File\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\File\Classes\CheckAccessToDeleteFileClass;
use App\Modules\File\Repositories\GetFileByUidRepository;
use Illuminate\Http\Request;

/**
 * Удалить фаил
 *
 */
class DeleteFileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $uid = $request->uid;
        $file = (new GetFileByUidRepository($uid))->run();
        if ((new CheckAccessToDeleteFileClass($file))->run()) {
            $file->delete();
            return true;
        }
    }


}
