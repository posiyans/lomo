<?php

namespace App\Modules\Article\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Modules\Article\Repositories\ArticleRepository;
use App\Modules\Article\Resources\AdminArticleResource;
use App\Modules\Article\Resources\ArticleResource;

/**
 * получить статью пользователем
 *
 */
class UserGetArticleController extends Controller
{

    public function index($slug)
    {
        try {
            $model = (new ArticleRepository())->slug($slug);
            $user = \Auth::user();
            if (!$user || !$user->ability('superAdmin', ['article-edit', 'article-show'])) {
                $model->public();
            }
            $article = $model->run();
            if ($article->status == 4 && (!$user || $user->owner)) {
                return new ArticleResource($article);
            }
            return new AdminArticleResource($article);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 404);
        }
    }
}
