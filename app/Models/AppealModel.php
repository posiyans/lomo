<?php

namespace App\Models;

use App\Models\Message\MessageModel;
use App\Models\MyModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class AppealModel extends MyModel
{
    use SoftDeletes;

    protected $table = 'appeals';
    protected $fillable = ['status', 'text'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if (Auth::check()) {
            $this->user_id = Auth::user()->id;
        } else {
            $this->user_id = 0;
        }
    }

    /**
     * отношение с пользователем
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }


    public function addMessage($text, $user_id){
        // todo почему не работает??????
        // $message = MessageModel::create(compact('text', 'user_id'));
        $message = new MessageModel();
        $message->text  = $text;
        $message->user_id  = $user_id;
        $this->message()->save($message);
    }
}
