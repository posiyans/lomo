<?php

namespace App\Modules\Article\Controllers;

use App\Http\Resources\AppealResource;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ConrtollerResource;
use App\Models\AppealModel;
use App\Models\Article\ArticleModel;
use App\Models\Article\CategoryModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * получить список категорий статей
 *
 */
class CategoryGetListController extends Controller
{


    /**
     * проверка на суперадмин или на доступ в админ панель
     */
    public function __construct()
    {
//        $this->middleware('ability:superAdmin,access-admin-panel');
    }

    public function index(Request $request)
    {
        $categories =  CategoryModel::whereNull('menu_name')->get();
        $rez = [];
        foreach ($categories as $item) {
            $rez[] = [
                'id' => $item->id,
                'label' => $item->name,
            ];
        }

        return $rez;
    }
}
