<?php

namespace App\Models;

use App\Models\Message\MessageModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\AppealModel
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $text
 * @property string $type
 * @property string $status
 * @property string|null $date_close
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, MessageModel> $message
 * @property-read int|null $message_count
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|AppealModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppealModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppealModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|AppealModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|AppealModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppealModel whereDateClose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppealModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppealModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppealModel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppealModel whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppealModel whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppealModel whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppealModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppealModel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppealModel withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|AppealModel withoutTrashed()
 * @mixin \Eloquent
 */
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


    public function addMessage($text, $user_id)
    {
        // todo почему не работает??????
        // $message = MessageModel::create(compact('text', 'user_id'));
        $message = new MessageModel();
        $message->text = $text;
        $message->user_id = $user_id;
        $this->message()->save($message);
    }
}
