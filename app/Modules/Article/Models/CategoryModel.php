<?php

namespace App\Modules\Article\Models;

use App\Models\MyModel;
use App\Modules\Article\Factories\CategoryModelFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Modules\SiteMenu\Models\CategoryModel
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $parent
 * @property int $show_menu
 * @property string|null $menu_name
 * @property int|null $position
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryModel whereMenuName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryModel whereParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryModel wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryModel whereShowMenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CategoryModel extends MyModel
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return CategoryModelFactory::new();
    }

    protected $casts = [
        'public' => 'boolean'
    ];

    protected $fillable = ['name', 'description', 'public'];

}
