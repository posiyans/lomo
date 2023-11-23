<?php

namespace App\Modules\Voting\Models;

use App\Models\MyModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

/**
 * App\Modules\Voting\Models\VotingFileModel
 *
 * @property int $id
 * @property string|null $uid
 * @property int $user_id кто загрузил
 * @property int|null $voting_id id голосования
 * @property int|null $stead_id id участка
 * @property string|null $hash
 * @property string|null $name
 * @property int|null $size
 * @property string|null $type
 * @property string|null $description
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @method static \Illuminate\Database\Eloquent\Builder|VotingFileModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VotingFileModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VotingFileModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|VotingFileModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|VotingFileModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingFileModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingFileModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingFileModel whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingFileModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingFileModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingFileModel whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingFileModel whereSteadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingFileModel whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingFileModel whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingFileModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingFileModel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingFileModel whereVotingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingFileModel withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|VotingFileModel withoutTrashed()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @mixin \Eloquent
 */
class VotingFileModel extends MyModel
{
    use SoftDeletes;

    protected $table = 'voting_files';

//    public function commentable()
//    {
//        return $this->morphTo();
//    }

    /**
     * File constructor.
     */
    public function __construct()
    {
        if (Auth::check()) {
            $this->user_id = Auth::user()->id;
        } else {
            $this->user_id = 0;
        }
        $this->uid = Uuid::uuid4()->toString();
    }

//    public static function saveForModel($file, $model) {
//
//    }


//    public static function check_availability($voting_id, $stead_id)
//    {
//        return self::where('stead_id', $stead_id)->where('voting_id', $voting_id)->first();
//    }


//    /**
//     *удалить страые файлы белутеней по данному голосованию и участку
//     */
//    public function deleteOldFile()
//    {
//        $files = self::where('stead_id', $this->stead_id)->where('voting_id', $this->voting_id)->get();
//        foreach ($files as $file) {
//            if ($file->id != $this->id) {
//                $file->delete();
//            }
//        }
//    }

}
