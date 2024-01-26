<?php

namespace App\Modules\Stead\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GetKadastrInfoController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,stead-show|stead-edit');
    }

    public function __invoke(Request $request)
    {
        $kadastr = $request->kadastr;
        $response = Http::withOptions([
            'verify' => false
        ])->get('https://pkk.rosreestr.ru/api/features/1/' . $kadastr);
        return $response->json();
    }


}
