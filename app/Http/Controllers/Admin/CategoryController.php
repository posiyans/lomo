<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\AppealResource;
use App\Http\Resources\ConrtollerResource;
use App\Models\AppealModel;
use App\Models\Article\CategoryModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{


    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-admin-panel');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->children) && $request->children === true){
            return CategoryModel::getListChildren(true);
        }
        $appeals =  CategoryModel::all();
        $rez = [];
        foreach ($appeals as $item) {
            $i = [];
            $i['id'] = $item->id;
            $i['show_menu'] = $item->show_menu;
            $i['menu_name'] = $item->menu_name;
            if ($item->menu_name){
                $i['basePath'] = $item->menu_name;
            } else {
                $i['basePath'] = '/article/list/'.$item->id;
            }
            $i['menu'] = $item->menu_name;
            $i['label'] = $item->name;

            $rez[] = $i;
        }

        return $rez;
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
        $user = Auth::user();
        if ($user->ability('superAdmin', 'edit-menu')) {
            if (isset($request->title) && !empty($request->title)) {
                $title = $request->title;
                $category = new CategoryModel();
                $category->name = $title;
                $category->save();
                return $category;
            }
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
        $user = Auth::user();
        if ($user->ability('superAdmin', 'edit-menu')) {
            if ($id == 'all') {
                $data = $request->allCategory;
                if (is_array($data)) {
                    return json_encode(CategoryModel::updateSort($data));
                }
            }
            $category = CategoryModel::find($id);
            if ($category) {
                $category->show_menu = $request->category['show_menu'];
                $category->menu_name = $request->category['menu_name'];
                $category->name = $request->category['label'];
                if ($category->save()) {
                    return json_encode(['status' => true, 'data' => $category]);
                }
            }
        }
        return false;
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
