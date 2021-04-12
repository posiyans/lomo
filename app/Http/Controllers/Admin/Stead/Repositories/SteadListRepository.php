<?php

namespace App\Http\Controllers\Admin\Stead\Repositories;

use App\Http\Controllers\Admin\AbstractAdminController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminSteadResource;
use App\Http\Resources\SteadResource;
use App\Models\Stead;
use Illuminate\Http\Request;

class SteadListRepository extends Controller
{


    public function findSteads($find = false)
    {
        $query = Stead::query();
        if($find && !empty($find)){
            $query->where('number', 'LIKE', '%'.$find.'%');
        }
        if ($this->paginate_limit) {
            $steads = $query->paginate($this->paginate_limit);
        } else {
            $steads = $query->get();
        }
        return $steads;
    }


}
