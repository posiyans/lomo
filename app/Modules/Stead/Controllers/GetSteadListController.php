<?php

namespace App\Modules\Stead\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Stead\Repositories\SteadRepository;
use App\Modules\Stead\Resources\SteadResource;
use App\Modules\Stead\Validators\GetSteadListValidator;


class GetSteadListController extends Controller
{

    public function index(GetSteadListValidator $request)
    {
        if ($request->page) {
            $steads = (new SteadRepository())->findByNumber($request->find)->findById($request->id)->paginate();
        } else {
            $steads = (new SteadRepository())->findByNumber($request->find)->findById($request->id)->run();
        }
        return SteadResource::collection($steads);
    }


}
