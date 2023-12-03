<?php

namespace App\Modules\Social\Models;

use App\Models\MyModel;
use App\Modules\User\Models\UserModel;

/**
 * App\Modules\Social\Models\LinkedSocialAccounts
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $email
 * @property string|null $provider_name
 * @property string|null $provider_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @property-read \App\Models\UserModel|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|LinkedSocialAccounts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LinkedSocialAccounts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LinkedSocialAccounts query()
 * @method static \Illuminate\Database\Eloquent\Builder|LinkedSocialAccounts whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LinkedSocialAccounts whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LinkedSocialAccounts whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LinkedSocialAccounts whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LinkedSocialAccounts whereProviderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LinkedSocialAccounts whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LinkedSocialAccounts whereUserId($value)
 * @mixin \Eloquent
 */
class LinkedSocialAccounts extends MyModel
{
    protected $fillable = ['provider_name', 'provider_id'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(UserModel::class);
    }

}
