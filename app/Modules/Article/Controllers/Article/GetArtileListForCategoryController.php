<?php

namespace App\Modules\Article\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Modules\Article\Models\ArticleModel;
use App\Modules\Article\Resources\ArticleResource;
use Illuminate\Http\Request;

/**
 * получить список статей
 *
 */
class GetArtileListForCategoryController extends Controller
{

    public function __invoke(Request $request)
    {
        // todo переделать на репозиторий
        $query = ArticleModel::query();
        $query->where(function ($query) {
            $query->where('status', 1)->orWhere('status', 4);
        });
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->sort) {
            if ($request->sort == '-time') {
                $query->orderBy('created_at', 'DESC');
            } else {
                if ($request->sort == '+time') {
                    $query->orderBy('created_at', 'ASC');
                } else {
                    if ($request->sort == '-id') {
                        $query->orderBy('id', 'DESC');
                    } else {
                        $query->orderBy('id', 'ASC');
                    }
                }
            }
        } else {
            $query->orderBy('id', 'ASC');
        }

        $appeal = $query->paginate($request->limit);
        return ArticleResource::collection($appeal);
    }
}
