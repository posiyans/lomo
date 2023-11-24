<?php

namespace App\Modules\Gardening\Repositories;

use App\Modules\Setting\Actions\GetGlobalOption;

class GardeningRepository
{

    private $namespace='gardening_';

    private $fields = [
        'name' => [
            'type' => 'string'
        ],
        'full_name' => [
            'type' => 'string'
        ],
        'PersonalAcc' => [
            'type' => 'string'
        ],
        'BankName' => [
            'type' => 'string'
        ],
        'BIC' => [
            'type' => 'string'
        ],
        'CorrespAcc' => [
            'type' => 'string'
        ],
        'PayeeINN' => [
            'type' => 'string'
        ],
        'updated_at' => [
            'type' => 'datetime'
        ],
    ];


    public function getNameSpace()
    {
        return $this->namespace;
    }

    public function getKeys()
    {
        return array_keys($this->fields);
    }



    public function all()
    {
        $rez = [];
        foreach ($this->fields as $key => $field) {
            $name = $this->namespace . $key;
            $val = GetGlobalOption::getOneValue($name, '');
            $rez[$key] = $val;
        }
        return $rez;
    }

}