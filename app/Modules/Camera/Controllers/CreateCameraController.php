<?php

namespace App\Modules\Camera\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Camera\Actions\CreateCameraAction;
use App\Modules\Camera\Validators\CreateCameraValidator;

/**
 * Обновить картинку с камеры
 *
 */
class CreateCameraController extends Controller
{

    public function __construct()
    {
//        $this->middleware('ability:superAdmin,camera-edit');
    }

    public function __invoke(CreateCameraValidator $request)
    {
        try {
            $data = [];
            $data['name'] = $request->name;
            $data['url'] = $request->url;
            $data['access'] = $request->get('name', 'all');
            $data['ttl'] = $request->get('ttl', 2190);

            (new CreateCameraAction($data))->run();
            return response(['status' => true]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
