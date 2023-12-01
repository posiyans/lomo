<?php

namespace App\Modules\Camera\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Camera\Actions\SaveImageFromCameraAction;
use App\Modules\Camera\Repositories\CameraRepository;
use Illuminate\Support\Facades\Cache;

/**
 * Обновить настройки камеры
 *
 */
class UpdateCameraController extends Controller
{

    public function __construct()
    {
//        $this->middleware('ability:superAdmin,camera-edit');
    }

    public function __invoke($id)
    {
        try {
            $id = (int)$id;
            $cacheName = CameraRepository::getCacheName($id);
            $model = (new CameraRepository())->getById($id);
//            if ($force) {
//            }
            Cache::tags('Camera')->forget($cacheName);
//            $cache = $this->getCache();
//            if ($cache) {
//                return $cache;
//            }
            $file = Cache::tags('Camera')->remember($cacheName, $model->ttl, function () use ($model) {
                return (new SaveImageFromCameraAction($model))->run();
            });
            if (file_exists($file)) {
                return response()->download($file, 'cam.jpg');
            }
        } catch (\Exception $e) {
        }
        return response(['errors' => 'Файл найден'], 450);
    }


}
