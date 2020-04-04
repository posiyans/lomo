<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\AppealResource;
use App\Http\Resources\ConrtollerResource;
use App\Models\AppealModel;
use App\Models\Article\ArticleModel;
use App\Models\Article\CategoryModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        if (isset($request->children) && $request->children){
//            return CategoryModel::getListChildren();
//        }
//        $appeal = AppealModel::all();
        $query =  ArticleModel::query();
        $appeal = $query->paginate($request->limit);
//        return  $query->paginate();
        return $appeal;
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
//        return $request;
        if (isset($request->title) && !empty($request->title)){
            $title = $request->title;
            $article = new ArticleModel();
            $article->title = $title;
            $article->uid = $request->uid;
            $article->resume = $request->resume;
            $article->text = $request->text;
            $article->category_id = $request->category_id;
            $article->public = $request->public;
            $article->allow_comments = !$request->comment_disabled;
            $article->publish_time = $request->display_time;
            if ($article->save()){
                if (isset($request->files) && is_array($request->files)) {
                    $article->attachedFiles($request->attached_files);
                }

            }
            return $article;
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $article = ArticleModel::find($id);
        if ($article  && $request->id == $id){
            $article->title = $request->title;
            $article->resume = $request->resume;
            $article->text = $request->text;
            $article->category_id = $request->category_id;
            $article->public = $request->public;
            $article->allow_comments = !$request->comment_disabled;
            $article->publish_time = $request->display_time;
            if ($article->save()){
                if (is_array($request->input('files'))) {
                    $article->attachedFiles($request->input('files'));
                }
            }
            $article = ArticleModel::find($id);
            $article->files;
            return $article;
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
