<?php
namespace App\Modules\Stead\Repositories;

use App\Modules\Stead\Models\SteadModel;

class GetSteadRepository
{

    private $query;

    public function __construct()
    {
        $this->query = SteadModel::query()->orderBy('id');
    }

    public function findByNumber($find)
    {
        if ($find) {
            $this->query->where('number', 'LIKE', '%' . $find . '%');
        }
        return $this;
    }

    public function findById($id)
    {
        if ($id) {
            $this->query->where('id', $id);
        }
        return $this;
    }

    public function paginate()
    {
        return $this->query->paginate();
    }

    public function run()
    {
        return $this->query->get();
    }


}