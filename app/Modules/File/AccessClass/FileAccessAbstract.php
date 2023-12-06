<?php

namespace App\Modules\File\AccessClass;

/**
 * Проверить на право удалить фаил
 *
 */
abstract class FileAccessAbstract
{
    public function read()
    {
        return false;
    }

    public function write()
    {
        return false;
    }

    public function delete()
    {
        return $this->write();
    }

}