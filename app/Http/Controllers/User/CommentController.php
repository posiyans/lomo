<?php

namespace App\Http\Controllers\User;

use App\Http\Resources\AppealResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\ConrtollerResource;
use App\Models\AppealModel;
use App\Models\Article\ArticleModel;
use App\Models\Article\CategoryModel;
use App\Models\Article\CommentModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->article_id)) {
            $query = CommentModel::query();
            $query->where('article_id', $request->article_id);
            $query->orderBy('id', 'ASC');
            $comments = $query->paginate($request->limit);
            return CommentResource::collection($comments);
        }
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
        if (isset($request->article_id) && isset($request->message)){
            $article = ArticleModel::find((int)$request->article_id);
            if ($article && $article->allow_comments == 1) {
                $comment = new CommentModel();
                $comment->user_id = Auth::user()->id;
                $comment->article_id = $article->id;
                $message = $request->message;
                $message = htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
                $message = nl2br($message);
                $comment->message = $message;
                if ($comment->save()){
                    return json_encode(['status'=>true, 'data'=>new CommentResource($comment)]);
                }
            }
        }
        return json_encode(['status'=>false]);
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
