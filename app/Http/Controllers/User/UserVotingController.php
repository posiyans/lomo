<?php

namespace App\Http\Controllers\User;

use App\Http\Resources\AppealResource;
use App\Http\Resources\ConrtollerResource;
use App\Http\Resources\VotingResource;
use App\Models\AppealModel;
use App\Models\Voting\AnswerModel;
use App\Models\Voting\VotingModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laratrust\Laratrust;

class UserVotingController extends Controller
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
        $query = VotingModel::query()->where('public', 1);
        if (isset($request->type)){
            $query->whereIn('type',  $request->type);
        }
        if (isset($request->status)){
            //todo не совсем так нужно рефакторить!!!!
            if (is_array($request->status)) {
                $status =  $request->status;
            } elseif (is_string($request->type)){
                $status = [$request->status];
            } else {
                $status = false;
            }
            if ($status){
                $query->whereIn('status',  $status, 'or');

            }


            $query->where('status', $request->status);
        }
        $votings = $query->paginate($request->limit);
        return VotingResource::collection($votings);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->voting;
        if ($data) {
            $voting = new VotingModel();
            $voting->fill($data);
            $voting->coments = 0;
            if ($voting->type == 'public'){
                $voting->date_start = '0000-01-01 00:00:00';
                $voting->date_stop = '9999-01-01 00:00:00';
            }
            $voting->save();
            if ($voting->save()) {
                if (isset($data['questions']) && is_array($data['questions'])) {
                    $voting->saveQuestions($data['questions']);
                }
                if (isset($data['files']) && is_array($data['files'])) {
                    $voting->attachedFiles($data['files']);
                }
                return json_encode(['status' => true, 'voting' => $voting, 'data' => $data]);
            }
            return json_encode(['voting' => $voting, 'data' => $data]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (is_numeric($id)) {
            $voting = VotingModel::where('id', $id)->where('public', 1)->first();
            if ($voting) {
                $user = Auth::user();
                if ($user){
                    $user_id = $user->id;
                } else {
                    $user_id = false;
                }
                return ['data'=>$voting->userReturn($user_id)];
//                return new VotingResource($voting);
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
        $user = Auth::user();
        if ($user->ability('superAdmin', 'сreate-polls')) {
            $data = $request->voting;
            if ($data) {
                $voting = VotingModel::find($id);
                if ($voting && $id == $voting->id) {
                    $voting->fill($data);
                    $voting->coments = 0;
                    if ($voting->type == 'public'){
                        $voting->date_start = '0000-01-01 00:00:00';
                        $voting->date_stop = '9999-01-01 00:00:00';
                    }
                    $voting->save();
                    if ($voting->save()) {
                        if (isset($data['questions']) && is_array($data['questions'])) {
                            $voting->saveQuestions($data['questions']);
                        }
                        if (isset($data['files']) && is_array($data['files'])) {
                            $voting->attachedFiles($data['files']);
                        }
                        return new VotingResource($voting);
                    }
                }
            }
        }
        return ['status'=>false];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }


    /**
     * получить голос пользователя
     */
    public function addAnswer(Request $request)
    {
        if (!Auth::guest() && $request->answer_id)
        {
            $user = Auth::user();
            if ($data = AnswerModel::userVoting($user->id, (int)$request->answer_id)){
                return json_encode(['status'=>true, 'data'=> $data]);
            }
            return json_encode(['status'=>false, 'data'=>  'На данный вопрос уже был получен ответ']);
        }
        return json_encode(['status'=>false, 'data'=>  'Вы не авторизованы']);

    }
}
