<?php

namespace App\Modules\Owner\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Owner\Repositories\OwnerFieldRepository;

class CreateOwnerUserController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,owner-edit');
    }

    public function __invoke()
    {
        return response(['data' => (new OwnerFieldRepository())->allToArray()]);
    }

}
