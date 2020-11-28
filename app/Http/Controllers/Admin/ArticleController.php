<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\AppealResource;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ConrtollerResource;
use App\Models\AppealModel;
use App\Models\Article\ArticleModel;
use App\Models\Article\CategoryModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{


    /**
     * проверка на суперадмин или на доступ в админ панель
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

        $query =  ArticleModel::query();
        if ($request->category){
            $query->where('category_id', $request->category);
        }
        if (isset($request->status)){
            $query->where('public', (int)$request->status);
        }
        if ($request->find){
            $find= explode(' ', mb_strtolower($request->find));
            foreach ($find as $item) {
                if (!empty(trim($item))) {
                    $query->whereRaw('lower(concat_ws(" ",title,resume, text)) like ?', '%' . $item . '%');
                }
            }
        }
        $article = $query->orderBy('id', 'desc')->paginate($request->limit);
        return ArticleResource::collection($article);
//        return $article;
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
     * Создание новой статьи
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->ability('superAdmin', 'сreate-article')) {
            if (isset($request->title) && !empty($request->title)) {
                $title = $request->title;
                $article = new ArticleModel();
                $article->title = $title;
                $article->uid = $request->uid;
                $article->resume = $request->resume;
                $article->text = $request->text;
                $article->category_id = $request->category_id;
                $article->public = $request->public;
                $article->allow_comments = $request->allow_comments;
                $article->news = $request->news;
                $article->publish_time = $request->display_time;
                if ($article->save()) {
                    if (isset($request->files) && is_array($request->files)) {
                        $article->attachedFiles($request->attached_files);
                    }

                }
                return $article;
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
    public function show($id)
    {
        if (is_numeric($id)) {
            $model = ArticleModel::find($id);
            $model->files = $model->files;
            return $model;
        }
        if (is_string($id)){
            return ['yps'];
        }
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
     * Обновление статьи
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->ability('superAdmin', 'сreate-article')) {
            $article = ArticleModel::find($id);
            if ($article && $request->id == $id) {
                $article->title = $request->title;
                $article->resume = $request->resume;
                $article->text = $request->text;
                $article->category_id = $request->category_id;
                $article->public = $request->public;
                $article->news = $request->news;
                $article->allow_comments = $request->allow_comments;
                $article->publish_time = $request->display_time;
                if ($article->save()) {
                    if (is_array($request->input('files'))) {
                        $article->attachedFiles($request->input('files'));
                    }
                }
                $article = ArticleModel::find($id);
                $article->files;
                return $article;
            }
        }
        return [false];
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
