<?php

namespace App\Modules\Stead\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article\ArticleModel;
use App\Models\Article\CategoryModel;
use App\Modules\Stead\Repositories\GetSteadByIdRepository;
use App\Modules\Stead\Resources\AdminSteadResource;
use App\Modules\Stead\Resources\SteadResource;
use Illuminate\Http\Request;


class GetSteadInfoController extends Controller
{


    /**
     * проверка на суперадмин или на доступ в админ панель
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $id = $request->get('id');
        $stead = (new GetSteadByIdRepository($id))->run();
        $user = \Auth::user();
        $access = false;
        if ($request->full && $user) {
            if ($user->ability('superAdmin', ['stead-show', 'stead-edit'])) {
                $access = true;
            }
            if ($user->owner && $user->owner->steads->where('id', $id)->isNotEmpty()) {
                $access = true;
            }
        }
        if ($access) {
            return new AdminSteadResource($stead);
        }
        return new SteadResource($stead);
    }


}
