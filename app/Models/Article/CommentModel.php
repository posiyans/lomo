<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Model;

class CommentModel extends Model
{
    //

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
