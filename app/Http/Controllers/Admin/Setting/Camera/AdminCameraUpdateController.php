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

class AdminCameraUpdateController extends Controller
{

    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-admin-panel');
    }

    public function index(Request $request)
    {
        $id = $request->post('id', false);
        if ($id) {
            $camera = CameraModel::find($id);
            if ($camera) {
                $camera->name = $request->post('name', $camera->name);
                $camera->url = $request->post('url', $camera->url);
                $camera->ttl = $request->post('ttl', $camera->ttl);
                if ($camera->save()) {
                    return ['status' => true, 'data' => $camera];
                }
            }
        }
        return ['status' => false];
    }

}
