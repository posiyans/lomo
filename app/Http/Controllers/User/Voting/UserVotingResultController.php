<?php

namespace App\Http\Controllers\User\Voting;

use App\Http\Controllers\Controller;
use App\Models\Voting\QuestionModel;
use App\Models\Voting\UserAnswerModel;
use Illuminate\Http\Request;

class UserVotingResultController extends Controller
{

//    /**
//     * проверка на суперадмин или на доступ а админ панель
//     */
//    public function __construct()
//    {
//        $this->middleware('ability:superAdmin,access-admin-panel');
//    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id = $request->question_id;
        if ($id) {
            $question = QuestionModel::find($id);
            if ($question) {
                $voting = $question->voting;
                $voting->calculateStatus();
                if ($voting->status == 'done') {
                    $userAnswers = UserAnswerModel::where('question_id', $id)->get();
                    $data = [];
                    foreach ($userAnswers as $userAnswer) {
                        $data[$userAnswer->stead_id] = $userAnswer->answer_id;
                    }
                    return ['status' => true, 'data' => $data];
                }
            }
        }
        return ['status' => false];
    }


}
