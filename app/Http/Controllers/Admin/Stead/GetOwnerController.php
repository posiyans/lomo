<?php

namespace App\Http\Controllers\Admin\Stead;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Owner\AdminOwnerSteadResource;
use App\Models\Stead;
use Illuminate\Support\Facades\Input;

class GetOwnerController extends Controller
{
    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-to-personal');
    }


    public function index($id)
    {
        $stead = Stead::find($id);
        if ($stead) {
            $owners = $stead->owners;
        }
        return ['status' => true, 'data' => AdminOwnerSteadResource::collection($owners)];
    }

}
