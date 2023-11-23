<?php

namespace App\Modules\File\Models;

use App\Models\MyModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

/**
 * App\Modules\File\Models\FileModel
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $commentable_type
 * @property int|null $commentable_id
 * @property string|null $commentable_uid
 * @property string|null $hash
 * @property string|null $name
 * @property int|null $size
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $uid uid файла
 * @property string $type тип файла
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $commentable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @method static \Illuminate\Database\Eloquent\Builder|FileModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FileModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FileModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|FileModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|FileModel whereCommentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileModel whereCommentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileModel whereCommentableUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileModel whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileModel whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileModel whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileModel whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileModel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileModel withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|FileModel withoutTrashed()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @mixin \Eloquent
 */
class FileModel extends MyModel
{
    use SoftDeletes;

    protected $table = 'files';


    public function commentable()
    {
        return $this->morphTo();
    }

    public function __construct(array $attributes = [])
    {
        if (Auth::check()) {
            $this->user_id = Auth::user()->id;
        }
        $this->uid = Uuid::uuid4()->toString();
        return parent::__construct($attributes);
    }


}
