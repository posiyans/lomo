<?php
namespace App\Modules\File\Repositories;

use App\Modules\File\Models\FileModel;

class GetObjectForFileRepository {

    private $file;

    public function __construct(FileModel $file)
    {
        $this->file = $file;
    }

    public function run()
    {
        $class = $this->file->commentable_type;
        $object = $class::where('uid', $this->file->commentable_uid)->first();
        if ($object) {
            return $object;
        }
        throw new \Exception('Модель не найдена');
    }


}