<?php

namespace App\Modules\Article\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Modules\Article\Models\CategoryModel;
use App\Modules\Setting\Actions\SetGlobalOption;
use Illuminate\Http\Request;

/**
 * Обновить данный раздела
 *
 */
class UpdateCategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,article-edit');
    }

    public function __invoke(CategoryModel $category, Request $request)
    {
        // todo validate
        $category->fill($request->only('name', 'description', 'public'));
        if ($request->setDefault) {
            SetGlobalOption::setOneValue('defaultCategoryId', $category->id);
        }
        $category->logAndSave('Обновление раздела');
        return $category;
    }
}
