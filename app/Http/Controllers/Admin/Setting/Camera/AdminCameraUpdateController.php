<?php

namespace App\Http\Controllers\Admin\Setting\Camera;

use App\Models\Settings\CameraModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
