<?php

namespace App\Modules\Camera\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Camera\Repositories\CameraRepository;
use Illuminate\Http\Request;

/**
 * Получить изображение с камеры камеру
 *
 */
class GetCameraImageController extends Controller
{

    public function __invoke(int $id, Request $request)
    {
        try {
            $file = CameraRepository::getImagePath($id);
            if (file_exists($file)) {
                return response()->download($file, 'cam_' . $id . '_' . time() . '.jpg');
            }
        } catch (\Exception $e) {
        }
        return response(['errors' => 'Файл найден'], 450);
    }


}
