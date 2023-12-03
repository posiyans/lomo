<?php

namespace App\Modules\Appeal\Repositories;

use App\Modules\Appeal\Modules\AppealTypeModel;

class AppealTypeRepository
{

    private $query;

    public function __construct()
    {
        $this->query = AppealTypeModel::query();
    }

    public function public()
    {
        $this->query->where('public', true);
        return $this;
    }


    public function all()
    {
        return $this->query->get();
    }


}