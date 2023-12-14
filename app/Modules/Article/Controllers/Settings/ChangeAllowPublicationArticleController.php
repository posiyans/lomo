<?php

namespace App\Modules\Article\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Modules\Article\Models\ArticleModel;
use App\Modules\Article\Validators\Settings\ChangeAllowPublicationArticleValidator;
use App\Modules\Setting\Actions\SetGlobalOption;

/**
 * сменить разрешение на предлагать публикацию
 *
 */
class ChangeAllowPublicationArticleController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,article-edit');
    }

    public function __invoke(ChangeAllowPublicationArticleValidator $request)
    {
        $value = $request->value;
        SetGlobalOption::setOneValue(ArticleModel::getAllowPublicationArticleSettingName(), $value);
        return ['status' => true, 'data' => $value];
    }
}
