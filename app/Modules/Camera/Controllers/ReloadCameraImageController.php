<?php

namespace App\Modules\Camera\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Camera\Repositories\CameraRepository;
use Illuminate\Support\Facades\Cache;

/**
 * Обновить картинку с камеры
 *
 */
class ReloadCameraImageController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,camera-edit');
    }

    public function __invoke($id)
    {
        $cacheName = CameraRepository::getCacheName($id);
        Cache::tags('Camera')->forget($cacheName);
        return response(['status' => true]);
    }


}
