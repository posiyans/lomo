<?php

namespace App\Modules\Stead\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Stead\Actions\CreateSteadAction;
use App\Modules\Stead\Validators\CreateSteadValidator;


class CreateSteadController extends Controller
{

    public function __invoke(CreateSteadValidator $request)
    {
        try {
            $stead = (new CreateSteadAction($request->number))->size($request->size)->run();
            return $stead;
        } catch (\Exception $e) {
            response($e->getMessage(), 450);
        }
    }

}
