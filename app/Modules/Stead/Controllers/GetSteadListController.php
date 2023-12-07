<?php

namespace App\Modules\Stead\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Stead\Repositories\SteadRepository;
use App\Modules\Stead\Resources\AdminSteadResource;
use App\Modules\Stead\Resources\SteadResource;
use App\Modules\Stead\Validators\GetSteadListValidator;
use Illuminate\Support\Facades\Auth;


class GetSteadListController extends Controller
{

    public function index(GetSteadListValidator $request)
    {
        if ($request->page) {
            $steads = (new SteadRepository())->findByNumber($request->find)->findById($request->id)->paginate($request->limit);
        } else {
            $steads = (new SteadRepository())->findByNumber($request->find)->findById($request->id)->run();
        }
        if ($request->admin && Auth::user() && Auth::user()->ability('superAdmin', ['owner-show', 'owner-edit'])) {
            return AdminSteadResource::collection($steads);
        }
        return SteadResource::collection($steads);
    }


}
