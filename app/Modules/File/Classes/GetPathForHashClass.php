<?php
namespace App\Modules\File\Classes;

class GetPathForHashClass
{
    public $hash;

    public function __construct($hash)
    {
        $this->hash = $hash;
    }

    public function run()
    {
        $folder = (new GetDirectoryPathClass())->run();
        $path = $folder . '/' . $this->getSmallHash();
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        return $path . '/' . $this->hash;
    }

    private function getSmallHash()
    {
        return substr($this->hash, 0,2);
    }

}