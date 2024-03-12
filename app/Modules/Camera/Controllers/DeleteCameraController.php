<?php

namespace App\Modules\Camera\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Camera\Actions\DeleteCameraAction;
use App\Modules\Camera\Repositories\CameraRepository;
use Illuminate\Http\Request;

/**
 * Удалить камеру
 *
 */
class DeleteCameraController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,camera-edit');
    }

    public function __invoke(int $id, Request $request)
    {
        try {
            $camera = CameraRepository::getById($id);
            (new DeleteCameraAction($camera))
                ->run();
            return response(['status' => true]);
        } catch (\Exception $e) {
            return response(['errors' => 'Файл найден'], $e->getMessage());
        }
    }


}
