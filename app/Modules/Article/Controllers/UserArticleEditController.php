<?php

namespace App\Modules\Article\Controllers;

use App\Http\Resources\AppealResource;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ConrtollerResource;
use App\Models\AppealModel;
use App\Models\Article\ArticleModel;
use App\Models\Article\CategoryModel;
use App\Modules\Article\Classes\CreateArticleClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * получить список категорий статей
 *
 */
class UserArticleEditController extends Controller
{


    /**
     * проверка на суперадмин или на доступ в админ панель
     */
    public function __construct()
    {
//        $this->middleware('ability:superAdmin,access-admin-panel');
    }

    public function addArticle(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->consent_to_email) {
            if ($user->isAbleTo('ban-comment')) {
                return response('Данное действие Вам запрещено', 410);
            }
            $opt = [
                'title' => $request->title ?? '',
                'text' => $request->text ?? '',
                'category_id' => $request->category_id,
                'uid' => $request->uid,
            ];
            $article = (new CreateArticleClass($opt))->status(2)->run();
            return $article;
        }
        return response('Ошибка доступа', 410);

    }
}
