<?php

namespace App\Modules\Article\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Modules\Article\Classes\CreateArticleClass;
use App\Modules\Article\Validators\Article\UserAddArticleValidator;

/**
 * предложить новсть пользователем
 *
 */
class UserAddArticleController extends Controller
{

    public function __invoke(UserAddArticleValidator $request)
    {
        $data = $request->validated();
        $article = (new CreateArticleClass($data))->status(2)->run();
        return $article;
    }
}
