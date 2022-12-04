<?php
namespace App\Modules\File\Repositories;

use App\Modules\File\Models\FileModel;

class GetFileByUidRepository {

    private $uid;

    public function __construct($uid)
    {
        $this->uid = $uid;
    }

    public function run()
    {
        $model = FileModel::where('uid', $this->uid)->first();
        if ($model) {
            return $model;
        }
        throw new \Exception('ФаЙл не найден');
    }


}