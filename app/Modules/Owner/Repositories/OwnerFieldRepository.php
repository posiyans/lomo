<?php

namespace App\Modules\Owner\Repositories;

use App\Modules\Owner\Models\OwnerUserFiledModel;

class OwnerFieldRepository
{

    private $query;

    public function __construct()
    {
        $this->query = OwnerUserFiledModel::query();
    }

    public function all()
    {
        return $this->query->orderBy('id')->get();
    }

    public function keys()
    {
        $result = [];
        foreach ($this->all() as $item) {
            $result[] = $item->name;
        }
        return $result;
    }


    public function allToArray()
    {
        $result = [];
        foreach ($this->all() as $item) {
            $result[] = [
                'name' => $item->name,
                'label' => $item->label,
                'type' => $item->type,
                'rules' => $item->options['rules'] ?? [],
            ];
        }
        return $result;
    }


}