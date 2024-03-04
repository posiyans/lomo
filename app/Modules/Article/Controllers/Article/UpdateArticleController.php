<?php

namespace App\Modules\Article\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Modules\Article\Models\ArticleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Обновить данный статьи
 *
 */
class UpdateArticleController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,article-edit');
    }

    public function __invoke(ArticleModel $article, Request $request)
    {
        if (is_numeric($id)) {
            $article = ArticleModel::find($id);
            if ($article) {
                $article->fill($request->all());
                if (empty($article->slug)) {
                    $article->slug = Str::slug($article->title);
                }
                if ($article->logAndSave('Изменение содержания')) {
                    return true;
                }
            }
            return false;
        }
    }
}
