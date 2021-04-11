<?php

namespace App\Http\Controllers\Admin\Owner\Repository;

use App\Http\Controllers\Controller;
use App\Models\Owner\OwnerUserModel;
use Illuminate\Support\Facades\Cache;


class GetOwnerRepository extends Controller
{


    /**
     * найти собственника
     *
     * @param $id id собственника
     * @return mixed OwnerUserModel
     * @throws \Exception
     */
    public function findById($id)
    {
        $owner = OwnerUserModel::find($id);
        if ($owner) {
            return $owner;
        }
        throw new \Exception('Собственник не найден');
    }

}

