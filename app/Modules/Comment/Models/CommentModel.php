<?php

namespace App\Modules\Comment\Models;

use App\Models\MyModel;
use App\Modules\Comment\Factories\CommentModelFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Modules\Comment\Models\CommentModel
 *
 * @property int $id
 * @property string $uid uid комментария
 * @property int $user_id id пользователя
 * @property string|null $commentable_type
 * @property string|null $commentable_uid
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message текст коментария
 * @property string|null $deleted_at
 * @property int|null $user_deletes_id id пользователя кто удалил
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array|null $options Доп опции
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read int|null $message_count
 * @method static \Illuminate\Database\Eloquent\Builder|CommentModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommentModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommentModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|CommentModel whereCommentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentModel whereCommentableUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentModel whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentModel whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentModel whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentModel whereUserDeletesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentModel whereUserId($value)
 * @property string|null $message текст коментария
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $commentable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @method static \App\Modules\Comment\Factories\CommentModelFactory factory(...$parameters)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $parentModel
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @mixin \Eloquent
 */
class CommentModel extends MyModel
{
    use HasFactory;

    protected $casts = [
        'options' => 'array'
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return CommentModelFactory::new();
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function parentModel(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'commentable_type', 'commentable_uid', 'uid');
    }
}
