<?php

namespace App\Models\Message;

use App\Models\MyModel;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\Message\MessageModel
 *
 * @deprecated 
 * @property int $id
 * @property int $user_id
 * @property string|null $channel_name
 * @property string|null $commentable_type
 * @property int|null $commentable_id
 * @property string|null $text
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $commentable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, MessageModel> $message
 * @property-read int|null $message_count
 * @method static \Illuminate\Database\Eloquent\Builder|MessageModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageModel whereChannelName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageModel whereCommentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageModel whereCommentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageModel whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageModel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageModel withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageModel withoutTrashed()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @mixin \Eloquent
 */
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
        if ($this->user_id) {
            return $this->hasOne(User::class, 'id', 'user_id');
        } else {
            return false;
        }
    }


}
