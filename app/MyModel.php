<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MyModel extends Model
{
    //
    public function log()
    {
        return $this->morphMany('App\Models\Log', 'commentable');
    }
    // public function file()
    // {
    //     return $this->morphMany('App\Models\File', 'commentable');
    // }
}
