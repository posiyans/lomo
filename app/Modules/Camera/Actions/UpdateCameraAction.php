<?php

namespace App\Modules\Camera\Actions;

use App\Modules\Setting\Models\GlobalOptionModel;

/**
 * создать камеру
 *
 */
class UpdateCameraAction
{
    private $camera;
    private $value;

    public function __construct(GlobalOptionModel $camera)
    {
        $this->camera = $camera;
        $this->value = $camera->value;
    }

    public function url($url)
    {
        if ($url) {
            $this->value['url'] = $url;
        }
        return $this;
    }

    public function ttl($ttl)
    {
        if ($ttl) {
            $this->value['ttl'] = $ttl;
        }
        return $this;
    }

    public function name($name)
    {
        if ($name) {
            $this->value['name'] = $name;
        }
        return $this;
    }


    /**
     * @throws \Exception
     */
    public function run()
    {
        $this->camera->value = $this->value;
        if ($this->camera->logAndSave('Изменение параметров камеры')) {
            return $this->camera;
        }
        throw new \Exception('Ошибка изменнеия параметров');
    }


}
