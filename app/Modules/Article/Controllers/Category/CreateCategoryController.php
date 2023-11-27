<?php

namespace App\Modules\Article\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Modules\Article\Models\CategoryModel;
use Illuminate\Http\Request;

/**
 * Создать данный раздела
 *
 */
class CreateCategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,article-edit');
    }

    public function __invoke(Request $request)
    {
        $category = new CategoryModel();
        $category->fill($request->only('name', 'description', 'public'));
        $category->logAndSave('Создание раздела');
        return $category;
    }
}
