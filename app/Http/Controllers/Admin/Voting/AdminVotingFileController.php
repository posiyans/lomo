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

class AdminVotingFileController extends Controller
{
    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-admin-panel');
    }


    public function getFile(Request $request) {
        $id = $request->id;
        $uid = $request->uid;
        $type = $request->type;
//        $user = Auth::user();
        $file = VotingFileModel::where('uid', $uid)->first();
        if ($file) {
            $path = '../storage/app/file/file/' . substr($file->hash, 0, 2) . '/' . $file->hash;
            if ($type == 'jpg' && $file->type == 'application/pdf') {
                $im = new \imagick($path.'[0]');
                $im->setImageFormat('jpg');
                header('Content-Type: image/jpeg');
                echo $im;
                return '';
            }
            if (file_exists($path)) {
                return response()->download($path, $file->name);
            }
        }
        return $this->response(['error'=>$path], 404);
    }

    public function uploadFile(Request $request)
    {
        if ($request->voting && $request->stead && Input::hasFile('file')) {
            $voitng_id = $request->voting;
            $stead_id = $request->stead;
            $user = Auth::user();
            $voting = VotingModel::find($voitng_id);
            $stead = Stead::find($stead_id);
            if ($voting && $stead) {
                $inputFile = Input::file('file');
                $data = $voting->addUserBelluten($inputFile,  $stead->id);
                return $data;
            }
        }
        return false;
    }

    public function getBulletinList(Request $request)
    {
        if ($request->voting && $request->stead) {
            $voitng_id = $request->voting;
            $stead_id = $request->stead;
            $voting = VotingModel::find($voitng_id);
            $stead = Stead::find($stead_id);
            if ($voting && $stead) {
                $items = VotingFileModel::where('stead_id', $stead->id)->where('voting_id', $voting->id)->get();
                if (count($items) > 0 ) {
                    $data = [];
                    foreach ($items as $item) {
                        $data[] = '/api/v1/admin/voting/owner/get-file?uid='. $item->uid;
                    }

                    return ['status' => true, 'data' => $data];
                }
            }
        }
        return ['status' => false];
    }
}
