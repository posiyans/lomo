<?php

namespace App\Modules\Gardening\Actions;

use App\Modules\Gardening\Repositories\GardeningRepository;
use App\Modules\Setting\Actions\SetGlobalOption;

class UpdateGardeningFieldAction
{

    private $field;
    private $value;

    public function __construct($field)
    {
        if (!in_array($field, (new GardeningRepository())->getKeys())) {
            throw new \Exception('Поле не найдено');
        }
        $this->field = $field;
    }

    public function value($value)
    {
        $this->value = $value;
        return $this;
    }

    public function run()
    {
        $namespace = (new GardeningRepository())->getNameSpace();
        $name = $namespace . $this->field;
        SetGlobalOption::setOneValue($name, $this->value);
    }

}
