<?php
namespace App\Modules\File\Classes;

class GetDirectoryPathClass
{
    public function run()
    {
        $path = config('filesystems.file_folder', __DIR__ . '/../../../../storage/files');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        return $path;
    }
}