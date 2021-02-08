<?php

namespace App\Http\Controllers\Storage;

use App\Models\Storage\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Input::hasFile('file')) {
            $inputFile = Input::file('file');
            $md5 = $this->md5_file($inputFile);
            $inputFile->move($md5['folder'], $md5['md5']);
            $file = new File();
            $file->hash = $md5['md5'];
            $file->name = $inputFile->getClientOriginalName();
            $file->size = $inputFile->getSize();
            if (isset($request->uid) && !empty($request->uid)) {
                $file->commentable_uid = $request->uid;
            }
            if (isset($request->model)) {
                if ($request->model == 'article') {
                    $file->commentable_type = 'App\Models\Article\ArticleModel';
                } else if ($request->model == 'user') {
                    $file->commentable_type = 'App\User';
//                    $file->commentable_id = $request->uid;

                }else if ($request->model == 'voting') {
                    $file->commentable_type = 'App\Models\Voting\VotingModel';
//                    $file->commentable_id = $request->uid;

                }
            }
            if ($request->model == 'avatar') {
                $file->description = 'avatar';
                if ($request->uid) {
                  $file->description .= '_'.$request->uid;
                }
            }
            if ($file->save()){
//                if ($request->model == 'user') {
//                    $user = Auth::user();
//                    $user->avatar = '/api/user/storage/file/'. $file->id;
//                    $user->save();
//                }
                $data= [
                    "files"=> [
                        "file"=> '/api/user/storage/file/'. $file->id,
                        'id'=>$file->id,
                    ]
                ];
                return $this->response($data);
            }
            return $this->response('error');
        }
        return $this->response('no file');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $user=Auth::user();
        $file = File::find($id);
        if ($file) {
            $path = '../storage/app/file/file/' . substr($file->hash, 0, 2) . '/' . $file->hash;
            if (file_exists($path)) {
                return response()->download($path, $file->name);
            }
        }
            return $this->response(['error'=>$path], 404);
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
