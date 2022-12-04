<?php
namespace App\Modules\File\Repositories;

use App\Modules\File\Models\FileModel;

class GetFilesForObjectRepository {

    private $query;

    public function __construct($model)
    {
        $this->query = FileModel::where('commentable_type', get_class($model))
            ->where('commentable_uid', $model->uid);
    }

    public function description($text)
    {
        $this->query->where('description', $text);
        return $this;
    }


    public function all()
    {
        return $this->query->get();
    }

    public function first()
    {
        $file = $this->query->orderBy('created_at', 'DESC')->first();
        if ($file) {
            return $file;
        }
        throw new \Exception('Файл не найден');
    }


}