<?php

namespace App\Http\Controllers\Admin\Setting\Camera;

use App\Models\Options\GlobalOptionModel;
use App\Models\Receipt\MeteringDevice;
use App\Models\Receipt\Rate;
use App\Models\Receipt\ReceiptType;
use App\Models\Settings\CameraModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\AbstractList;
use phpDocumentor\Reflection\Types\Mixed_;

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
