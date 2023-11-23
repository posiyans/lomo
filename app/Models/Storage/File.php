<?php

namespace App\Models\Storage;

use App\Modules\File\Models\FileModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\Storage\File
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
 * @property string|null $deleted_at
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
 * @method static \Illuminate\Database\Eloquent\Builder|File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|File query()
 * @method static \Illuminate\Database\Eloquent\Builder|File whereCommentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereCommentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereCommentableUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|File withoutTrashed()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @mixin \Eloquent
 */
class File extends FileModel
{
    use SoftDeletes;

    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * File constructor.
     */
    public function __construct()
    {
        if (Auth::check()) {
            $this->user_id = Auth::user()->id;
        }
    }

//    public static function saveForModel($file, $model) {
//
//    }

}
