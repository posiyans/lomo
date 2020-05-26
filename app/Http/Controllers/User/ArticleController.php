<?php

namespace App\Http\Controllers\User;

use App\Http\Resources\ArticleResource;
use App\Http\Resources\CommentResource;
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
        $query =  ArticleModel::query();
        $query->where('publish_time', '<', NOW());
        $query->where('public', '1');
        $query->where('news', '1');
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->sort) {
            if ($request->sort == '-time'){
                $query->orderBy('publish_time', 'DESC');
            } else if ($request->sort == '+time') {
               $query->orderBy('publish_time', 'ASC') ;
            } else if ($request->sort == '-id') {
                $query->orderBy('id', 'DESC') ;
            } else {
                $query->orderBy('id', 'ASC') ;
            }

        } else {
            $query->orderBy('id', 'ASC') ;
        }

        $appeal = $query->paginate($request->limit);
        return ArticleResource::collection($appeal);
//        return json_encode(['data'=>$data,'total'=>$appeal->total()]);
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
////        return $request;
//        if (isset($request->title) && !empty($request->title)){
//            $title = $request->title;
//            $article = new ArticleModel();
//            $article->title = $title;
//            $article->uid = $request->uid;
//            $article->resume = $request->resume;
//            $article->text = $request->text;
//            $article->category_id = $request->category_id;
//            $article->public = $request->public;
//            $article->allow_comments = !$request->comment_disabled;
//            $article->publish_time = $request->display_time;
//            if ($article->save()){
//                if (isset($request->files) && is_array($request->files)) {
//                    $article->attachedFiles($request->attached_files);
//                }
//
//            }
//            return $article;
//        }
//        return false;
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = ArticleModel::where('uid', $id)->first();
        if (!$model){
            $model = ArticleModel::find((int)$id);
        }
        if ($model){
            $model->files = $model->files;
//            $model->comments = $model->comments;
            $model->comments_show = CommentResource::collection($model->comments);
            return $model;

        }
        return false;

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
//
//        $article = ArticleModel::find($id);
//        if ($article  && $request->id == $id){
//            $article->title = $request->title;
//            $article->resume = $request->resume;
//            $article->text = $request->text;
//            $article->category_id = $request->category_id;
//            $article->public = $request->public;
//            $article->allow_comments = !$request->comment_disabled;
//            $article->publish_time = $request->display_time;
//            if ($article->save()){
//                if (is_array($request->input('files'))) {
//                    $article->attachedFiles($request->input('files'));
//                }
//            }
//            $article = ArticleModel::find($id);
//            $article->files;
//            return $article;
//        }
//        return [false];
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
