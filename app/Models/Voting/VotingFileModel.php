<?php

namespace App\Models\Voting;

use App\MyModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

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


    public static function check_availability($voting_id, $stead_id)
    {
        return self::where('stead_id', $stead_id)->where('voting_id', $voting_id)->first();
    }


    /**
     *удалить страые файлы белутеней по данному голосованию и участку
     */
    public function deleteOldFile()
    {
        $files = self::where('stead_id', $this->stead_id)->where('voting_id', $this->voting_id)->get();
        foreach ($files as $file) {
            if ($file->id != $this->id) {
                $file->delete();
            }
        }
    }

}
