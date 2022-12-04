<?php

namespace App\Modules\Article\Controllers;

use App\Http\Resources\AppealResource;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ConrtollerResource;
use App\Models\AppealModel;
use App\Models\Article\ArticleModel;
use App\Models\Article\CategoryModel;
use App\Modules\Article\Repositories\GetArticleByUidRepository;
use App\Modules\Article\Repositories\GetFilesForArticleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * получить список категорий статей
 *
 */
class GetFilesForArticleController extends Controller
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
        $article =  (new GetArticleByUidRepository($request->uid))->run();
        // todo проверить права доступа
        $files = (new GetFilesForArticleRepository($article))->run();
        return $files;
    }
}
