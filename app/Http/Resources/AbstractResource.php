<?php


namespace common\modules;


abstract class AbstractResource
{
    protected $__model;

    public function toArray()
    {
        return $this->__model->getAttributes($this->__model->attributes());
    }

    public static function collection(array $models)
    {
        $data = [];
        foreach ($models as $model) {
            $data[] =  (new static($model))->toArray();
        }
        return $data;
    }

    public function __get($name)
    {
        if (property_exists($this->__model, $name) || $this->__model->hasAttribute($name)) {
            return $this->__model->{$name};
        }
        return false;
    }


}


