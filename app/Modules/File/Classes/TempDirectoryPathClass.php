<?php
namespace App\Modules\File\Classes;

class TempDirectoryPathClass
{
    public function get()
    {
        $path  = __DIR__.'/../../../../storage/tmp';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        return $path;
    }
}