<?php

namespace App\Http\Controllers\Admin\Setting\Camera;

use App\Models\Settings\CameraModel;
use App\Http\Controllers\Controller;

class AdminCameraRefreshController extends Controller
{

    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-admin-panel');
    }

    public function index($id)
    {
        $camera = CameraModel::find($id);
        if ($camera) {
            $camera->updateCache(true);
            if ($camera->save()) {
                return ['status' => true];
            }
        }

        return ['status' => false];
    }

}
