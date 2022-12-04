<?php

namespace App\Modules\File\Models;

use App\Models\MyModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class FileModel extends MyModel
{
    use SoftDeletes;

    protected $table = 'files';


    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * File constructor.
     */
    public function __construct()
    {
        $this->user_id = 0;
        if (Auth::check()) {
            $this->user_id = Auth::user()->id;
        }
    }


}
