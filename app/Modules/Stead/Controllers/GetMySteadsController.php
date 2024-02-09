<?php

namespace App\Modules\Stead\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class GetMySteadsController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:owner');
    }

    public function __invoke()
    {
        $user = Auth::user();
        $owner = $user->owner;
        $steads = $owner->steads->sortBy('stead.number', SORT_NATURAL)->map(function ($item) {
            return [
                'id' => $item->id,
                'number' => $item->number,
                'proportion' => $item->pivot->proportion,
            ];
        })->values();
        return response(['data' => $steads]);
    }


}
