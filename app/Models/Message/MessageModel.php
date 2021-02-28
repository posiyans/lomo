<?php

namespace App\Models\Message;

use App\Models\MyModel;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class MessageModel extends MyModel
{
    use SoftDeletes;

    protected $fillable = ['text', 'user_id', 'channel_name'];

    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * Message constructor.
     */
    public function __construct()
    {
        if (Auth::check()) {
            $this->user_id = Auth::user()->id;
        }
    }

    /**
     * отношение с пользователем
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        if ($this->user_id){
            return $this->hasOne(User::class, 'id', 'user_id');
        } else {
            return false;
        }

    }



}
