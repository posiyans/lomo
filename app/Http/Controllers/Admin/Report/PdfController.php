<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Modules\Article\Models\ArticleModel;
use App\Modules\Article\Notifications\NewArticleUnderModeration;
use App\Modules\User\Models\UserModel;
use Illuminate\Support\Facades\Notification;

class PdfController extends Controller
{

    public function __invoke()
    {
        $user = UserModel::find(1);
        $article = ArticleModel::find(1);
        Notification::send($user, new NewArticleUnderModeration($article));
        dd($user);
    }

}
