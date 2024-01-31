<?php

namespace App\Modules\Setting\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Receipt\Validators\ChangeFaviconValidator;


/**
 * @refactory
 *
 *  todo доделать!!
 * Сменить иконку приложения
 */
class ChangeFaviconController extends Controller
{


    public function __construct()
    {
        $this->middleware('role:superAdmin');
    }

    public function __invoke(ChangeFaviconValidator $request)
    {
        try {
            $file = $request->favicon;
            return response(['status' => true]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
