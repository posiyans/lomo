<?php

namespace App\Modules\BanUser\Models;

use App\Models\MyModel;
use App\Modules\User\Models\UserModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

/**
 * App\Modules\BanUser\Models\BanUserModel
 *
 * @property int $id
 * @property string $uid
 * @property int $user_id
 * @property string|null $commentable_type
 * @property string|null $commentable_uid
 * @property string|null $end_ban_time
 * @property int $author_id
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @property-read UserModel|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|BanUserModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BanUserModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BanUserModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BanUserModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|BanUserModel whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BanUserModel whereCommentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BanUserModel whereCommentableUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BanUserModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BanUserModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BanUserModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BanUserModel whereEndBanTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BanUserModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BanUserModel whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BanUserModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BanUserModel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BanUserModel withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BanUserModel withoutTrashed()
 * @mixin \Eloquent
 */
class BanUserModel extends MyModel
{
    use HasFactory;
    use SoftDeletes;

    public function __construct(array $attributes = [])
    {
        if (Auth::check()) {
            $this->author_id = Auth::user()->id;
        } else {
            $this->author_id = null;
        }
        $this->uid = Uuid::uuid4()->toString();
        parent::__construct($attributes);
    }

    protected $fillable = ['end_ban_time'];

    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }
}
