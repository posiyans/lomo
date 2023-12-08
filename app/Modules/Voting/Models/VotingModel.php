<?php

namespace App\Modules\Voting\Models;

use App\Models\MyModel;

/**
 * App\Modules\Voting\Models\VotingModel
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $date_publish
 * @property string|null $date_start время начала
 * @property string|null $date_stop время скончания
 * @property string $type
 * @property int $comments
 * @property string $status
 * @property array|null $answer
 * @property string|null $result
 * @property string|null $uid
 * @property int $public
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Voting\QuestionModel> $questions
 * @property-read int|null $questions_count
 * @method static \Illuminate\Database\Eloquent\Builder|VotingModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VotingModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VotingModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|VotingModel whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingModel whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingModel whereDatePublish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingModel whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingModel whereDateStop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingModel wherePublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingModel whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingModel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingModel whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingModel whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingModel whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VotingModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class VotingModel extends MyModel
{
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'answer' => 'array',
    ];

    protected $fillable = [
        'title',
        'date_publish',
        'date_start',
        'date_stop',
        'description',
        'type',
        'comments',
        'status',
        'answer',
        'public',
    ];
    protected $guarded = ['id'];
//    protected $fillable = [ 'title' ];


//{ key: 'new', display_name: 'Новое' },
//{ key: 'execution', display_name: 'Идет' },
//{ key: 'done', display_name: 'Законченно' },
//{ key: 'cancel', display_name: 'Отмененное' }

    public function questions()
    {
        return $this->hasMany('App\Models\Voting\QuestionModel', 'voting_id');
    }

//    /**
//     * сколько человек участввавало в голоосвании
//     * берется максимальное ко-во человек ответивших на вопрос (если несколько вопросов)
//     *
//     * @return int
//     */
//    public function userAnswer(){
//        $count = 0;
//        foreach ($this->questions as $question) {
//            if ($question->allAnswers() > $count){
//                $count = $question->allAnswers();
//            }
//        }
//        return $count;
//    }


//    /**
//     * сохранить вопросы для голосования
//     *
//     * @param $questions
//     */
//    public function saveQuestions($questions){
//        if (is_array($questions)) {
//            foreach ($questions as $question) {
//                if (isset($question['id'])){
//                    $questionModel  = QuestionModel::find($question['id']);
//                } else {
//                    $questionModel = new QuestionModel();
//                    $questionModel->voting_id = $this->id;
//                }
//                if ($question['text'] == '' ){
//                    $questionModel->delete();
//                } else {
//                    $questionModel->text = $question['text'];
//                    if ($questionModel->save()) {
//                        if (isset($question['answers'])) {
//                            $questionModel->saveAnswers($question['answers']);
//                        }
//                    }
//                }
//            }
//        }
//    }

//    public function userReturn($user_id = false)
//    {
//        $this->calculateStatus();
//        $data = [];
//        if ($this->type == 'public') {
//            return $this->retrunPublicVotinForUser();
//        }
//        if ($this->type == 'owner') {
//            return $this->retrunOwnerVotinForUser();
//        }
//        return $data;
//
//    }


//    public function calculateStatus()
//    {
//        $datetime = new \DateTime();
//         if ($this->status !== 'cancel' || $this->status !== 'done') {
//            if ($this->type == 'public') {
//                if (strtotime($this->date_publish) < $datetime->format('U')) {
//                    $this->status = 'execution';
//                }
//            } else {
//                if (strtotime($this->date_start) < $datetime->format('U')) {
//                    $this->status  = 'execution';
//                }
//                if (strtotime($this->date_stop . ' 23:59:59') < $datetime->format('U')) {
//                    $this->status  = 'done';
//                }
//            }
//        }
//    }
//
//
//    /**
//     * вернуть публичное голосование
//     *
//     * @return array
//     */
//    public function retrunPublicVotinForUser()
//    {
//        $data = [
//            'id' => $this->id,
//            'title' => $this->title,
//            'description' => $this->description,
//            'type' => $this->type,
//            'comments' => $this->comments,
//            'status' => $this->status,
//            'files' => $this->files,
//            'questions' => QuestionResource::collection($this->questions),
//            'created_at' => $this->updated_at,
//        ];
//        $questions = [];
//        foreach ($this->questions as $question) {
//            $answers = [];
//            $myAnswer = $question->checkUserAnswer();
//            foreach ($question->answers as $answer) {
//                $answers[] =  [
//                    'id'=>$answer->id,
//                    'text' => $answer->text,
//                    'isMyAnswer' => $myAnswer ? $myAnswer->answer_id == $answer->id ? true : false : false,
//                    'userAnswersCount' => count($answer->userAnswers),
//                ];
//            }
//            $tmp_quest = [
//                'id' => $question->id,
//                'text' => $question->text,
//                'voting_id' => $question->voting_id,
//                'answersCount' => $question->allAnswers(),
//                'answers' => $answers,
//                'myAnswers' => $myAnswer ? $myAnswer->answer_id ? $myAnswer->answer_id : false : false,
//            ];
//            $questions[] = $tmp_quest;
//        }
//        $data['questions'] = $questions;
//        return $data;
//    }

//    /**
//     *  вернуть голосование собственников
//     *
//     * @return array
//     */
//    public function retrunOwnerVotinForUser()
//    {
//        $data = [
//            'id' => $this->id,
//            'title' => $this->title,
//            'description' => $this->description,
//            'type' => $this->type,
//            'comments' => $this->comments,
//            'status' => $this->status,
//            'files' => $this->files,
//            'questions' => QuestionUserResource::collection($this->questions),
//            'created_at' => $this->updated_at,
//            'date_start' => $this->date_start,
//            'date_stop' => $this->date_stop,
//            'date_publish' => $this->updated_at,
//            'steadsCount'=> Stead::all()->count(),
//        ];
//        if ($this->status == 'execution' || $this->status == 'done') {
//            foreach ($this->questions as $question) {
//                $array[] = $question->id;
//            }
//            $steads = UserAnswerModel::query()
//                ->whereIn('question_id', $array)
//                ->select('stead_id')
//                ->distinct()
//                ->get();
//            $temp = [];
//            foreach ($steads as $item) {
//                $temp[$item->stead_id] = $item->stead->number;
//            }
//           $data['voted'] = $temp;
//        }
//        if ($this->status == 'done') {
//            $questions = [];
//            foreach ($this->questions as $question) {
//                $answers = [];
//                foreach ($question->answers as $answer) {
//                    $answers[] =  [
//                        'id'=>$answer->id,
//                        'text' => $answer->text,
//                        'userAnswersCount' => count($answer->userAnswers),
//                    ];
//                }
//                $tmp_quest = [
//                    'id' => $question->id,
//                    'text' => $question->text,
//                    'voting_id' => $question->voting_id,
//                    'answersCount' => $question->allAnswers(),
//                    'answers' => $answers,
//                ];
//                $questions[] = $tmp_quest;
//            }
//            $data['questions'] = $questions;
//        }
//        return $data;
//    }

//    public function syncOwnerUserAnswer($answer, $stead_id)
//    {
//        DB::beginTransaction();
//        $error = false;
//        foreach ($this->questions as $question) {
//            if (array_key_exists($question->id, $answer)) {
//                $question->setOwnerAnswer($answer[$question->id], $stead_id);
//            } else {
////                $question->deleteOwnerAnswer($stead_id, $user_id);
//                $error = true;
//            }
//        }
//        if ($error) {
//            DB::rollback();
//            return false;
//        }
//        DB::commit();
//        return true;
//    }
//
//    public function addUserBelluten($inputFile,  $stead_id)
//    {
//        $file = new VotingFileModel();
//        $md5 = Controller::md5_file($inputFile);
//        $inputFile->move($md5['folder'], $md5['md5']);
//        $file->hash = $md5['md5'];
//        $file->name = $inputFile->getClientOriginalName();
//        $file->size = $inputFile->getSize();
//        $file->type = $inputFile->getClientMimeType();
//        $file->voting_id = $this->id;
//        $file->stead_id = $stead_id;
//        $file->description = 'stead=' . $stead_id . ', voting=' . $this->id;
//        if ($file->logAndSave('Добавлен бюллетень', $stead_id)){
//            $file->deleteOldFile();
//            $data= [
//                'status' => true,
//                'file' => [
//                    'url' => '/api/v1/admin/voting/owner/get-file?uid='. $file->uid,
//                    'id'=>$file->id,
//                ]
//            ];
//            return $data;
//        }
//        return false;
//
//    }


}
