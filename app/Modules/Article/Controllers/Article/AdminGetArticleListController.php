<?php

namespace App\Modules\Article\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Modules\Article\Models\ArticleModel;
use App\Modules\Article\Resources\AdminArticleListResource;
use Illuminate\Http\Request;

/**
 * получить список статей
 *
 */
class AdminGetArticleListController extends Controller
{


    public function __construct()
    {
        $this->middleware('ability:superAdmin,article-show,article-edit');
    }

    public function index(Request $request)
    {
        $query = ArticleModel::query();
        if ($request->category) {
            $query->where('category_id', $request->category);
        }
        if (isset($request->status)) {
            $query->where('public', (int)$request->status);
        }
        if ($request->find) {
            $find = explode(' ', mb_strtolower($request->find));
            foreach ($find as $item) {
                if (!empty(trim($item))) {
                    $query->whereRaw('lower(concat_ws(" ",title,resume, text)) like ?', '%' . $item . '%');
                }
            }
        }
        $article = $query->orderBy('id', 'desc')->paginate($request->limit);
        return AdminArticleListResource::collection($article);
    }
}
