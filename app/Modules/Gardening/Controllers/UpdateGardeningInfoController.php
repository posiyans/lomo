<?php

namespace App\Modules\Gardening\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Gardening\Actions\UpdateGardeningFieldAction;
use App\Modules\Gardening\Repositories\GardeningRepository;
use Illuminate\Http\Request;

class UpdateGardeningInfoController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,settings-gardening');
    }

    public function __invoke(Request $request)
    {
        $rez = [];
        $data = $request->only((new GardeningRepository())->getKeys());
        foreach ($data as $key => $datum) {
            $value = $datum ?? '';
            (new UpdateGardeningFieldAction($key))->value($value)->run();
            $rez[$key] = $datum;
        }
        (new UpdateGardeningFieldAction('updated_at'))->value(date('Y-m-d H:i:s'))->run();
        return $rez;
    }

}
