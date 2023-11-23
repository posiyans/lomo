<?php

namespace App\Modules\Stead\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article\ArticleModel;
use App\Models\Article\CategoryModel;
use App\Modules\Stead\Repositories\SteadRepository;
use App\Modules\Stead\Resources\SteadResource;
use Illuminate\Http\Request;


class GetSteadListController extends Controller
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
        $find = $request->get('find', '');
        $id = $request->get('id', '');
        if ($request->page) {
            $steads = (new SteadRepository())->findByNumber($find)->findById($id)->paginate();
        } else {
            $steads = (new SteadRepository())->findByNumber($find)->findById($id)->run();
        }
        return SteadResource::collection($steads);
    }


}
