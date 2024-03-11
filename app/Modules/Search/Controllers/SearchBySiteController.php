<?php

namespace App\Modules\Search\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Search\Actions\FindTextBySite;
use App\Modules\Search\Validators\SearchBySiteValidator;


/**
 * @refactory
 *
 *  todo доделать!!
 * Сменить иконку приложения
 */
class SearchBySiteController extends Controller
{


//    public function __construct()
//    {
//        $this->middleware('role:superAdmin');
//    }

    public function __invoke(SearchBySiteValidator $request)
    {
        try {
            $find = $request->find;
            $result = (new FindTextBySite($find))->run();

            return response(['status' => true, 'find' => $find, 'data' => $result]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
