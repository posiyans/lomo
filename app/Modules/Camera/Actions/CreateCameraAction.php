<?php

namespace App\Modules\Camera\Actions;

use App\Modules\Camera\Repositories\CameraRepository;
use App\Modules\Setting\Actions\GetGlobalOption;
use App\Modules\Setting\Actions\SetGlobalOption;

/**
 * создать камеру
 *
 */
class CreateCameraAction
{
    private $camera;

    public function __construct($options)
    {
        $this->camera = $options;
    }

    public function run()
    {
        $items = GetGlobalOption::getAll(CameraRepository::getOptionName());
        $this->camera['id'] = count($items) + 1;
        SetGlobalOption::addOneValue(CameraRepository::getOptionName(), $this->camera);
    }


}
