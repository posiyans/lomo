<?php

namespace App\Modules\File\Repositories;


use Illuminate\Support\Facades\Storage;

class PreviewFileRepository
{
    private $width = 800;
    private $height = 0;
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function width($width = 600)
    {
        $this->width = $width;
        return $this;
    }

    public function height($height = 600)
    {
        $this->height = $height;
        return $this;
    }


    public function path()
    {
        return $this->createPreview();
    }


    /**
     * создать превью файла и положить его в кеш
     *
     **/
    public function createPreview()
    {
        $hash = md5_file(Storage::path($this->path));
        $preview_name = $hash . '_' . $this->width . '_' . $this->height;
        $preview_path = 'cache/preview/' . substr($hash, 0, 2) . '/';
        if (!Storage::exists($preview_path . $preview_name)) {
            $filePath = Storage::path($this->path);
            $im = new \imagick($filePath);
            $im->setImageFormat('jpg');
            try {
                $im->thumbnailImage($this->width, $this->height, true, true);
            } catch (\Exception $e) {
                $im = new \imagick($filePath);
                $im->setImageFormat('jpg');
                $im->thumbnailImage($this->width, $this->height);
            }
            $tmpName = tempnam(sys_get_temp_dir(), 'preview_file_');
            $im->writeImage($tmpName);
            Storage::putFileAs($preview_path, $tmpName, $preview_name);
        }
        return $preview_path . $preview_name;
    }


}