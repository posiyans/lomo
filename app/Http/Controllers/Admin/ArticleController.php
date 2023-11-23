<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Article\Models\ArticleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
                $article->slug = Str::slug($title);
                $article->publish_time = date('Y-m-d H:i:s');
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
        if (is_string($id)) {
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
        if ($user->ability('superAdmin', 'article-edit')) {
            $article = ArticleModel::find($id);
            if ($article && $request->id == $id) {
                $article->fill($request->all());
//                $article->title = $request->title;
//                $article->resume = $request->resume;
//                $article->text = $request->text;
//                $article->category_id = $request->category_id;
//                $article->public = $request->public;
//                $article->news = $request->news;
//                $article->allow_comments = $request->allow_comments;
//                $article->publish_time = $request->display_time;
                $article->slug = Str::slug($article->title);
                if ($article->logAndSave('Изменение содержания')) {
//                    if (is_array($request->input('files'))) {
//                        $article->attachedFiles($request->input('files'));
//                    }
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
