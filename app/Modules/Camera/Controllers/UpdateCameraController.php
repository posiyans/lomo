<?php

namespace App\Modules\Camera\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Camera\Actions\UpdateCameraAction;
use App\Modules\Camera\Repositories\CameraRepository;
use Cache;
use Illuminate\Http\Request;

/**
 * Обновить настройки камеры
 *
 */
class UpdateCameraController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,camera-edit');
    }

    public function __invoke(int $id, Request $request)
    {
        try {
            $camera = CameraRepository::getById($id);
            (new UpdateCameraAction($camera))
                ->url($request->url)
                ->ttl($request->ttl)
                ->name($request->name)
                ->run();
            $cacheName = CameraRepository::getCacheName($id);
            Cache::tags('Camera')->forget($cacheName);
            return response(['status' => true,]);
        } catch (\Exception $e) {
            return response(['errors' => 'Файл найден'], $e->getMessage());
        }
    }


}
