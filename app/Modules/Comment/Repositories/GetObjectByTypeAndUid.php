<?php

namespace App\Modules\Comment\Repositories;

/**
 * Получить обьект для которой сделан комментарий
 *
 */
class GetObjectByTypeAndUid
{

    private $object;

    public function __construct($type, $uid)
    {
        $class = CommentTypeRepository::getClassName($type);
        $this->object = $class::where('uid', $uid)->first();
    }

    public function run()
    {
        if ($this->object) {
            return $this->object;
        }
        throw new \Exception('Модель не найдена');
    }
}