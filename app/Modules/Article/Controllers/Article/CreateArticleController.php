<?php

namespace App\Modules\Article\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Modules\Article\Actions\CreateArticleAction;
use App\Modules\Article\Validators\Article\CreateArticleValidator;

/**
 * Добавить статью
 *
 */
class CreateArticleController extends Controller
{

    public function __invoke(CreateArticleValidator $request)
    {
        try {
            $article = (new CreateArticleAction($request->validated()))->run();
            return response(['data' => $article]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }
}
