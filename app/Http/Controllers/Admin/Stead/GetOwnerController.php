<?php

namespace App\Http\Controllers\Admin\Stead;

use App\Http\Resources\Admin\Owner\AdminOwnerSteadResource;
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

class GetOwnerController extends Controller
{
    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-to-personal');
    }


    public function index($id)
    {
        $stead = Stead::find($id);
        if ($stead) {
            $owners = $stead->owners;
        }
       return ['status' => true, 'data' => AdminOwnerSteadResource::collection($owners)];

    }

}
