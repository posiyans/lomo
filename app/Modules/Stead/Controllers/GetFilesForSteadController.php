<?php

namespace App\Modules\Stead\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article\ArticleModel;
use App\Models\Article\CategoryModel;
use App\Modules\Stead\Models\SteadModel;
use App\Modules\Stead\Resources\FileForSteadResource;
use Illuminate\Http\Request;


class GetFilesForSteadController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,stead-show|stead-edit');
    }

    public function __invoke(SteadModel $stead, Request $request)
    {
        $files = $stead->files;
        return response(['data' => FileForSteadResource::collection($files)]);
//        $id = $request->get('id');
//        $stead = (new GetSteadByIdRepository($id))->run();
//        $user = \Auth::user();
//        $access = false;
//        if ($user) {
//            if ($user->ability('superAdmin', ['stead-show', 'stead-edit'])) {
//                $access = true;
//            }
//            if ($user->owner && $user->owner->steads->where('id', $id)->isNotEmpty()) {
//                $access = true;
//            }
//        }
//        if ($request->full && $access) {
//            return new AdminSteadResource($stead);
//        }
//        return new SteadResource($stead);
    }


}
