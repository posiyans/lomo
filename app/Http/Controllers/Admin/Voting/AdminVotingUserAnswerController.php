<?php

namespace App\Http\Controllers\Admin\Voting;

use App\Http\Resources\Admin\Voting\AdminVotingResultResource;
use App\Http\Resources\AppealResource;
use App\Http\Resources\ConrtollerResource;
use App\Http\Resources\VotingResource;
use App\Models\AppealModel;
use App\Models\Stead;
use App\Models\Voting\UserAnswerModel;
use App\Models\Voting\VotingFileModel;
use App\Models\Voting\VotingModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class AdminVotingUserAnswerController extends Controller
{
    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-admin-panel');
    }



    public function addUserAnswer(Request $request)
    {
        if ($request->voting && $request->stead && $request->answers) {
            $voitng_id = $request->voting;
            $stead_id = $request->stead;
            $user = Auth::user();
            $voting = VotingModel::find($voitng_id);
            $stead = Stead::find($stead_id);
            if ($voting && $stead) {
                if (count($request->answers) > 0 && VotingFileModel::check_availability($voting->id, $stead->id)) {
                    if ($voting->syncOwnerUserAnswer($request->answers, $stead->id)) {
                        return ['status' => true];
                    }
                }
            }
        }
        return ['status' => false, 'data' => 'Ошибка сохранения данных'];
    }



}
