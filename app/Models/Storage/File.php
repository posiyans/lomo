<?php

namespace App\Models\Storage;

use App\MyModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class File extends MyModel
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
