<?php

namespace App\Modules\Voting\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Voting\Repositories\GetQuestionsForVotingRepository;
use App\Modules\Voting\Repositories\GetVotingByIdRepository;
use App\Modules\Voting\Resources\QuestionAndAnswersResource;
use Illuminate\Http\Request;


class GetQuestionsForVotingController extends Controller
{


    /**
     * проверка на суперадмин или на доступ в админ панель
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        try {
            $id = $request->get('id');
            $andAnswers = $request->get('andAnswers', false);
            $voting = (new GetVotingByIdRepository($id))->run();
            //todo проверить права доступа
            $questions = (new GetQuestionsForVotingRepository($voting))->run();
            if ($andAnswers) {
                $questions = QuestionAndAnswersResource::collection($questions);
            }
            return $questions;
        } catch (\Exception $e) {
            response(['errors' => $e->getMessage()], 420);
        }
    }


}
