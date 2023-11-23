<?php

namespace App\Modules\Article\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Article\Repositories\GetArticleByUidRepository;
use App\Modules\Article\Repositories\GetFilesForArticleRepository;
use Illuminate\Http\Request;

/**
 *
 * получить список файлов для статьи
 *
 */
class GetFilesForArticleController extends Controller
{

    public function index(Request $request)
    {
        $article = (new GetArticleByUidRepository($request->uid))->run();
        // todo проверить права доступа
        $files = (new GetFilesForArticleRepository($article))->run();
        return $files;
    }
}
