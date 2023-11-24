<?php

namespace App\Modules\Gardening\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gardening;
use App\Modules\Gardening\Repositories\GardeningRepository;
use Illuminate\Http\Request;

class GetGardeningInfoController extends Controller
{

    public function __invoke()
    {
        return (new GardeningRepository())->all();
    }

}
