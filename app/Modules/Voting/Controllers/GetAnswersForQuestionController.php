<?php

namespace App\Modules\Voting\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Voting\Repositories\GetQuestionByIdRepository;
use Illuminate\Http\Request;


class GetAnswersForQuestionController extends Controller
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
            $question = (new GetQuestionByIdRepository($id))->run();
            return $question->answers;
        } catch (\Exception $e) {
            response(['errors' => $e->getMessage()], 420);
        }
    }


}
