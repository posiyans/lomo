<?php
namespace App\Modules\File\Repositories;

use App\Modules\Article\Models\ArticleModel;

class GetObjectByType
{
    private $objectArray = [
        'article' => ArticleModel::class
    ];
    private $object;
    private $test;

    public function __construct($type, $uid)
    {
        $this->test = $type;
        if (isset($this->objectArray[$type])) {
            $class = $this->objectArray[$type];
            $this->test = $this->objectArray[$type];
            $this->object = $class::where('uid', $uid)->first();
        }
    }

    public function run()
    {
//        return [$this->objectArray, $this->test,'article' == $this->test,  in_array($this->test, $this->objectArray)];
        if ($this->object) {
            return $this->object;
        }
        throw new \Exception('Модель не найдена');
    }
}