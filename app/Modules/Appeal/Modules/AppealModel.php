<?php

namespace App\Modules\Appeal\Modules;

use App\Models\Message\MessageModel;
use App\Models\MyModel;
use App\Modules\Comment\Interfaces\CommentedInterface;
use App\Modules\File\Models\FileModel;
use App\Modules\User\Models\UserModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * App\Modules\Appeal\Modules\AppealModel
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
 * @property-read UserModel|null $user
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
class AppealModel extends MyModel implements CommentedInterface
{
    use SoftDeletes;

    protected $fillable = ['title', 'text', 'appeal_type_id', 'uid'];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if (Auth::check()) {
            $this->user_id = Auth::user()->id;
        } else {
            $this->user_id = 0;
        }
//        $this->uid = Str::uuid();
    }

    /**
     * модель пользователя создавшего обращения
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }

    /**
     * прикрепленные файлы
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function files()
    {
        return $this->morphMany(FileModel::class, 'commentable', null, 'commentable_uid', 'uid');
    }

    /**
     * модель пользователя закрывшего обращения
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function close_user()
    {
        return $this->hasOne(UserModel::class, 'id', 'close_user_id');
    }

    /**
     * отношение с типом
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function type()
    {
        return $this->hasOne(AppealTypeModel::class, 'id', 'appeal_type_id');
    }

    /**
     * @return string|null
     */
    public static function uid()
    {
        return 'uid';
    }


    public function accessRead(UserModel $user): bool
    {
        if ($this->user_id === $user->id) {
            return true;
        }
        if ($user->ability('superAdmin', ['appeal->edit', 'appeal-show'])) {
            return true;
        }
        return false;
    }

    public function commentWrite(UserModel $user): bool
    {
        if ($this->user_id === $user->id) {
            return true;
        }
        if ($user->ability('superAdmin', ['appeal->edit'])) {
            return true;
        }
        return false;
    }

}
