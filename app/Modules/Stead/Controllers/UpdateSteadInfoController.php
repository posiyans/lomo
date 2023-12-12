<?php

namespace App\Modules\Stead\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article\ArticleModel;
use App\Models\Article\CategoryModel;
use App\Modules\Stead\Models\SteadModel;
use Illuminate\Http\Request;


class UpdateSteadInfoController extends Controller
{

    public function __invoke(SteadModel $stead, Request $request)
    {
        return $stead;
//        $stead = (new GetSteadByIdRepository($id))->run();
//        $user = \Auth::user();
//        if ($request->full && $user && $user->ability('superAdmin', ['stead-show', 'stead-edit'])) {
//            return new AdminSteadResource($stead);
//        }
//        return new SteadResource($stead);
    }


}
