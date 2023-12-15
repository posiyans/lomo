<?php

namespace App\Modules\Article\Models;

use App\Models\MyModel;
use App\Modules\Article\Factories\ArticleModelFactory;
use App\Modules\Comment\Interfaces\CommentedInterface;
use App\Modules\File\Models\FileModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Modules\Article\Models\ArticleModel
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $uid
 * @property string|null $resume
 * @property string|null $text
 * @property int|null $category_id
 * @property int $public
 * @property int $news
 * @property int $allow_comments
 * @property string $publish_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id id автора статьи
 * @property string|null $slug url статьи
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @property-read \App\Modules\User\Models\UserModel|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleModel whereAllowComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleModel whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleModel whereNews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleModel wherePublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleModel wherePublishTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleModel whereResume($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleModel whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleModel whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleModel whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleModel whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleModel whereUserId($value)
 * @mixin \Eloquent
 */
class ArticleModel extends MyModel implements CommentedInterface
{
    use HasFactory;

    protected $fillable = ['title', 'text', 'resume', 'category_id', 'uid', 'status', 'allow_comments', 'slug'];

    protected $hidden = [];

    protected $casts = [
//        'allow_comments' => 'boolean',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return ArticleModelFactory::new();
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Modules\User\Models\UserModel', 'user_id', 'id');
    }

    public function files()
    {
        return $this->morphMany(FileModel::class, 'commentable', null, 'commentable_uid', 'uid');
    }

    /**
     * @return string|null
     */
    public static function uid()
    {
        return 'uid';
    }


    public static function getAllowPublicationArticleSettingName()
    {
        return 'AllowPublicationArticle';
    }

}
