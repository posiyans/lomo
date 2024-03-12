<?php

namespace App\Modules\Camera\Actions;

use App\Modules\Setting\Models\GlobalOptionModel;

/**
 * Удалить камеру
 *
 */
class DeleteCameraAction
{
    private $camera;

    public function __construct(GlobalOptionModel $camera)
    {
        $this->camera = $camera;
    }

    /**
     * @throws \Exception
     */
    public function run()
    {
        if ($this->camera->logAndDelete('Удаление камеры')) {
            return true;
        }
        throw new \Exception('Ошибка изменнеия параметров');
    }


}
