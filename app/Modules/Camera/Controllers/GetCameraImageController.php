<?php

namespace App\Modules\Camera\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Camera\Repositories\CameraRepository;
use App\Modules\File\Repositories\PreviewFileRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Получить изображение с камеры камеру
 *
 */
class GetCameraImageController extends Controller
{

    public function __invoke(int $id, Request $request)
    {
        try {
            ignore_user_abort(true);
            set_time_limit(500);
            $file = CameraRepository::getImagePath($id);
            $user = Auth::user() ?? false;

            if (!$user || !$user->ability(['superAdmin', 'owner'], ['camera-show'])) {
                $file = (new PreviewFileRepository($file))->width(200)->path();
            }
            if (Storage::exists($file)) {
                return Storage::download($file, 'cam_' . $id . '_' . time() . '.jpg');
            }
        } catch (\Exception $e) {
        }
        return response(['errors' => 'Файл найден'], 450);
    }


}
