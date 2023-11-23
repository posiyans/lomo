<?php

namespace App\Modules\File\Classes;

class GetPathForHashClass
{
    private $hash;

    public function __construct($hash)
    {
        $this->hash = $hash;
    }


    /**
     * Вернуть только папку до файла
     *
     * @return string
     */
    public function onlyFolder()
    {
        $folder = (new GetDirectoryPathClass())->run();
        $path = $folder . '/' . $this->getSmallHash();
        return $path;
    }

    /**
     * вернуть полный путь до файла
     *
     * @return string
     */
    public function run()
    {
        return $this->onlyFolder() . '/' . $this->hash;
    }

    private function getSmallHash()
    {
        return substr($this->hash, 0, 2);
    }


}