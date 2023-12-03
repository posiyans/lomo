<?php

namespace App\Http\Controllers\Admin\Voting;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Voting\AdminVotingResultResource;
use App\Models\Voting\UserAnswerModel;
use App\Models\Voting\VotingModel;
use Illuminate\Http\Request;

class AdminVotingResultController extends Controller
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
        $voting_id = $request->voting_id;
        $steads_array = $request->steads;
        $voting = VotingModel::find($voting_id);
        if ($voting) {
            $question_id = [];
            foreach ($voting->questions as $item) {
                $question_id[] = $item->id;
            }
            $results = UserAnswerModel::query()->whereIn('stead_id', $steads_array)->whereIn('question_id', $question_id)->get();
//            $result = UserAnswerModel::query()->get();
            return AdminVotingResultResource::collection($results);
        }
        return false;
    }
//        $query = VotingModel::query();
//        $votings = $query->paginate($request->limit);
//        return VotingResource::collection($votings);
//    }
//
//    /**
//     * Show the form for creating a new resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function create()
//    {
//        //
//    }
//
//    /**
//     * Store a newly created resource in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @return \Illuminate\Http\Response
//     */
//    public function store(Request $request)
//    {
//        $data = $request->voting;
//        if($data){
//            $voting = new VotingModel();
//            $voting->fill($data);
//            $voting->save();
//            if ($voting->save()){
//                if (isset($data['questions']) && is_array($data['questions'])) {
//                   $voting->saveQuestions($data['questions']);
//                }
//                if (isset($data['files']) && is_array($data['files'])) {
//                    $voting->attachedFiles($data['files']);
//                }
//                return json_encode(['status'=> true, 'voting'=> $voting, 'data'=>$data]);
//            }
////            return $data;
//            return json_encode(['voting'=> $voting, 'data'=>$data]);
//        }
//    }
//
//    /**
//     * Display the specified resource.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function show($id)
//    {
//        if (is_numeric($id)){
//            $voting = VotingModel::find($id);
//            if ($voting){
//                return new VotingResource($voting);
//            }
//        }
//
//        return [];
//
//    }
//
//    /**
//     * Show the form for editing the specified resource.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function edit($id)
//    {
//        return 'edit';
//    }
//
//    /**
//     * Update the specified resource in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function update(Request $request, $id)
//    {
//        $data = $request->voting;
//        if($data){
//            $voting = VotingModel::find($id);
//            if ($voting && $id == $voting->id) {
//                $voting->fill($data);
//                $voting->save();
//                if ($voting->save()){
//                    if (isset($data['questions']) && is_array($data['questions'])) {
//                        $voting->saveQuestions($data['questions']);
//                    }
//                    if (isset($data['files']) && is_array($data['files'])) {
//                        $voting->attachedFiles($data['files']);
//                    }
//                    return new VotingResource($voting);
//                }
//            }
//        }
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function destroy($id)
//    {
//        //
//    }
}
