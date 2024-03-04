<?php

namespace App\Modules\Article\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Modules\Article\Models\ArticleModel;
use App\Modules\Article\Resources\AdminArticleResource;
use Illuminate\Http\Request;

/**
 * получить статью админом
 *
 */
class AdminGetArticleController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,article-show,article-edit');
    }

    public function __invoke(ArticleModel $article, Request $request)
    {
        return new AdminArticleResource($article);
    }
}
