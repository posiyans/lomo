<?php

namespace App\Models\Storage;

use App\Modules\File\Models\FileModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class File extends FileModel
{
    use SoftDeletes;

    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * File constructor.
     */
    public function __construct()
    {
        if (Auth::check()) {
            $this->user_id = Auth::user()->id;
        }
    }

//    public static function saveForModel($file, $model) {
//
//    }

}
