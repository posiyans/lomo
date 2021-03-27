<?php

namespace App\Http\Controllers\All\Camera;

use App\Http\Controllers\Controller;
use App\Models\Settings\CameraModel;
use Illuminate\Http\Request;


class UserCameraController extends Controller
{


    /**
     * получить списак камер
     *
     * @param Request $request
     * @return array|false[]
     */
    public function list(Request $request)
    {
        $camers = CameraModel::getListForUser();
        return ['status' => true, 'data' => $camers];
    }

    public function getImage($id)
    {
       $model = CameraModel::find($id);
       if($model && $model->access == 'all') {
           if (file_exists($model->getImgPath())) {
               return response()->download($model->getImgPath(), 'cam.jpg');
           }
       }
    }

}
