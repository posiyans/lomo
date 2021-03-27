<?php

namespace App\Models\Options;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalOptionModel extends Model
{
    use HasFactory;

    protected $casts = [
        'value' => 'array',
    ];


    public static function addOption($name, $value)
    {
        $item = new self();
        $item->name = $name;
        $item->setValue($value);
       if ($item->save()) {
           return $item;
       }
       return false;
    }

    public function setValue($value)
    {
        $this->type = gettype($value);
        if (is_array($value)) {
            $this->value = $value;
        } else {
            $this->value = [$value];
        }
    }

    public function getValue()
    {
        return $this->value;
    }

    public static function getOptionList($name)
    {
       $items = self::where('name', $name)->get();
       $data = [];
        foreach ($items as $item) {
            $data[] = ['id' => $item->id, 'value' => $item->getValue()];
       }
        return $data;
    }



}
