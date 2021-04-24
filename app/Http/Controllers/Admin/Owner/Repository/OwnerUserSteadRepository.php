<?php

namespace App\Http\Controllers\Admin\Owner\Repository;

use App\Http\Controllers\Controller;
use App\Models\Owner\OwnerUserModel;
use App\Models\Owner\OwnerUserSteadModel;
use Illuminate\Support\Facades\Cache;


class OwnerUserSteadRepository extends Controller
{


    /**
     * найти связь участка и собственика
     *
     * @param $id id связи собственника и участка
     * @return mixed OwnerUserSteadModel
     * @throws \Exception
     */
    public function findById($id)
    {
        $owner = OwnerUserSteadModel::find($id);
        if ($owner) {
            return $owner;
        }
        throw new \Exception('Участок не найден');
    }

}

