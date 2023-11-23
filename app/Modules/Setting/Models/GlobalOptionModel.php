<?php

namespace App\Modules\Setting\Models;

use App\Models\MyModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Modules\Setting\Models\GlobalOptionModel
 *
 * @property int $id
 * @property string $name названия свойства
 * @property array $value значение
 * @property string $type тип данных
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|GlobalOptionModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GlobalOptionModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GlobalOptionModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|GlobalOptionModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GlobalOptionModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GlobalOptionModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GlobalOptionModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GlobalOptionModel whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GlobalOptionModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GlobalOptionModel whereValue($value)
 * @mixin \Eloquent
 */
class GlobalOptionModel extends MyModel
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

    /**
     * @param $name
     * @return array
     * @deprecated
     */
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
