<?php
namespace App\Modules\Stead\Repositories;


use App\Modules\Stead\Models\SteadModel;


class GetSteadByIdRepository {

    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function run()
    {

        $model = SteadModel::where('id', $this->id)->first();
        if ($model) {
            return $model;
        }
        throw new \Exception('Участок не найден');
    }


}