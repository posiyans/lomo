<?php

namespace App\Http\Controllers\Admin\Voting;

use App\Http\Resources\Admin\Voting\AdminVotingQuestionResource;
use App\Http\Resources\AppealResource;
use App\Http\Resources\ConrtollerResource;
use App\Http\Resources\VotingResource;
use App\Models\AppealModel;
use App\Models\Voting\VotingModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laratrust\Laratrust;

class VotingQuestionController extends Controller
{

    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-admin-panel');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        $query = VotingModel::query();
//        $votings = $query->paginate($request->limit);
//        return VotingResource::collection($votings);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Получить админом вопросы по голосованию
     *
     * @param  int  $id голосования
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (is_numeric($id)) {
            $voting = VotingModel::find($id);
            if ($voting) {
                $questions  = $voting->questions;
                return AdminVotingQuestionResource::collection($questions);
            }
        }
        return [];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return 'edit';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
