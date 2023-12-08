<?php

namespace App\Modules\Article\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Modules\Article\Actions\CreateArticleAction;
use App\Modules\Article\Notifications\NewArticleUnderModeration;
use App\Modules\Article\Validators\Article\UserAddArticleValidator;
use App\Modules\User\Models\UserModel;
use Illuminate\Support\Facades\Notification;

/**
 * предложить новсть пользователем
 *
 */
class UserAddArticleController extends Controller
{

    public function __invoke(UserAddArticleValidator $request)
    {
        $data = $request->validated();
        $article = (new CreateArticleAction($data))->status(2)->run();
        $user = UserModel::find(70);
        Notification::send($user, new NewArticleUnderModeration($article));
        return $article;
    }
}
