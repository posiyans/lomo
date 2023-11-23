<?php

namespace App\Modules\Voting\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article\ArticleModel;
use App\Models\Article\CategoryModel;
use App\Modules\Voting\Repositories\GetQuestionByIdRepository;
use App\Modules\Voting\Resources\QuestionResultInfoResource;
use Illuminate\Http\Request;


class GetVotingResultQuestionController extends Controller
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
        $id = $request->get('id');
        $question = (new GetQuestionByIdRepository($id))->run();
        $result = new QuestionResultInfoResource($question);
        return $result;
    }


}
