<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AppealModel;
use App\Models\Article\CategoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cat = false;
        if (isset($request->children) && $request->children == true) {
            $cat = CategoryModel::getListChildren();
            if (Auth::check() && Auth::user()->ability('superAdmin', 'access-admin-panel')) {
                $cat[] = ['label' => 'Админ панель', 'basePath' => '/admin'];
            }
        }
        return $cat;
//        $query =  CategoryModel::query();
//        $appeal = $query->paginate($request->limit);
//        return 'sdsadas';
//        return $appeal;


        $rez = [];
        $rez[] = ['label' => 'Главная', 'basePath' => '/index'];
        if ($cat) {
            $rez[] = ['label' => 'Статьи', 'basePath' => '/article/list', 'children' => $cat];
        }
        $rez[] = [
            'label' => 'Информация',
            'basePath' => '/',
            'children' => [
                ['label' => 'Тарифы', 'basePath' => '/modules/rates'],
                ['label' => 'Квитанции', 'basePath' => '/modules/receipt'],
                ['label' => 'Погода', 'basePath' => '/modules/weather'],
                ['label' => 'Камеры', 'basePath' => '/modules/camera'],
            ]
        ];
        if (Auth::check() && Auth::user()->ability('superadmin', 'access-admin-panel')) {
            $rez[] = ['label' => 'Админ панель', 'basePath' => '/admin/my-dashboard'];
        }
        return $cat;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (isset($request->title) && !empty($request->title)) {
            $title = $request->title;
            $category = new CategoryModel();
            $category->name = $title;
            $category->save();
            return $category;
        }
        return false;
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AppealModel $apppel)
    {
        return $apppel;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
//        if ($id == 'all'){
//            $data = $request->allCategory;
//            if (is_array($data)){
//                return json_encode(CategoryModel::updateSort($data));
//            }
//        }
//        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
