<?php

namespace App\Modules\Article\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Modules\Article\Repositories\ArticleCategoryRepository;
use App\Modules\Setting\Actions\GetGlobalOption;
use Illuminate\Http\Request;

/**
 * получить список категорий статей
 *
 */
class GetListCategoryController extends Controller
{

    public function __invoke(Request $request)
    {
        $public = $request->public;
        $mark = $request->markDefault;
        $categoriesRepository = (new ArticleCategoryRepository());
        if ($public) {
            $categoriesRepository->public();
        }
        $categories = $categoriesRepository->all();
        if ($mark) {
            $defaultId = GetGlobalOption::getOneValue('defaultCategoryId', 1);
            foreach ($categories as $item) {
                if ($item->id == $defaultId) {
                    $item->default = true;
                }
            }
        }
        return $categories;
    }
}
