<?php

namespace App\Http\Controllers\Admin\Setting\Camera;

use App\Http\Controllers\Controller;
use App\Models\Settings\CameraModel;


class AdminCameraGetListController extends Controller
{

    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-admin-panel');
    }

    public function index()
    {
        $camera = CameraModel::getAllCamers();
        return ['status' => true, 'data' => $camera];
    }

}
