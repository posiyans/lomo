<?php

namespace App\Modules\File\Controllers;

use App\Http\Resources\AppealResource;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ConrtollerResource;
use App\Models\AppealModel;
use App\Models\Article\ArticleModel;
use App\Models\Article\CategoryModel;
use App\Modules\File\Classes\CheckAccessToFileClass;
use App\Modules\File\Classes\GetPathForHashClass;
use App\Modules\File\Classes\SaveFileForObjectClass;
use App\Modules\File\Classes\TempDirectoryPathClass;
use App\Modules\File\Repositories\GetFileByUidRepository;
use App\Modules\File\Repositories\GetObjectByType;
use App\Modules\File\Repositories\GetObjectForFileRepository;
use App\Modules\File\Resources\FileResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

/**
 * получить фаил
 *
 */
class GetFileController extends Controller
{


    /**
     * проверка на суперадмин или на доступ в админ панель
     */
    public function __construct()
    {
//        $this->middleware('ability:superAdmin,access-admin-panel');
    }

    public function index(Request $request)
    {
         $uid = $request->uid;
         $file = (new GetFileByUidRepository($uid))->run();
         if ((new CheckAccessToFileClass($file))->run()) {
             $path = (new GetPathForHashClass($file->hash))->run();
             if (file_exists($path)) {
                 return response()->download($path, $file->name);
             }
             return response('Файл не найден', 450);
         }
    }


}
