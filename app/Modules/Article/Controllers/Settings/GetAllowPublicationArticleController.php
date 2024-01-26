<?php

namespace App\Modules\Article\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Modules\Article\Actions\AllowPublicationArticleAction;
use App\Modules\Article\Models\ArticleModel;
use App\Modules\Setting\Actions\GetGlobalOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * получить возможность пользователю  предлогать статью
 *
 */
class GetAllowPublicationArticleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request)
    {
        try {
            $user = Auth::user();
            $access = GetGlobalOption::getOneValue(ArticleModel::getAllowPublicationArticleSettingName(), 1);
            if ($request->admin && $user->hasRole('superAdmin')) {
                return ['data' => $access];
            }
            (new AllowPublicationArticleAction($access))->run($user);
            return ['data' => $access];
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 403);
        }
        // 1 - отключенно
        // 2 - собственники
        // 3 - все
    }

}
