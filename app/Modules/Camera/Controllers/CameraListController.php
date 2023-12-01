<?php

namespace App\Modules\Camera\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Camera\Repositories\CameraRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * получить список камер
 *
 */
class CameraListController extends Controller
{

    public function __invoke(Request $request)
    {
        $full = false;
        if ($request->full && Auth::user() && Auth::user()->ability('superAdmin', ['camera-edit'])) {
            $full = true;
        }
        $camera = (new CameraRepository())->all($full);
        return ['data' => $camera];
    }


}
