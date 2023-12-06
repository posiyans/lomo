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
        $uid_name = $class::uid();
        $this->object = $class::where($uid_name, $uid)->first();
    }

    public function run()
    {
        if ($this->object) {
            return $this->object;
        }
        throw new \Exception('Модель не найдена');
    }
}