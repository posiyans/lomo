<?php

namespace App\Modules\Camera\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Camera\Repositories\CameraRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Создать камеру
 *
 */
class GetCameraImageController extends Controller
{

    public function __invoke(Request $request)
    {
//        try {
        $id = (int)$id;
        $model = (new CameraRepository())->getById($id);
        $admin = Auth::user() && Auth::user()->ability('superAdmin', ['camera-edit']);
        if (true || $model->access == 'all' || $admin) {
            if (file_exists(CameraRepository::getImagePath($id))) {
                return response()->download(CameraRepository::getImagePath($id), 'cam.jpg');
            }
        } else {
            echo 'sdfsdf';
        }
//        } catch (\Exception $e) {
//        }
//        return response(['errors' => 'Файл найден'], 450);
    }


}
