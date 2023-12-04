<?php

namespace App\Modules\Appeal\Modules;

use App\Models\Message\MessageModel;
use App\Models\MyModel;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
class AppealModel extends MyModel
{
    use SoftDeletes;

    protected $fillable = ['title', 'text', 'appeal_type_id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if (Auth::check()) {
            $this->user_id = Auth::user()->id;
        } else {
            $this->user_id = 0;
        }
        $this->uid = Str::uuid();
    }

    /**
     * отношение с пользователем
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }

    /**
     * отношение с типом
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function type()
    {
        return $this->hasOne(AppealTypeModel::class, 'id', 'appeal_type_id');
    }

}
