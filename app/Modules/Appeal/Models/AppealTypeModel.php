<?php

namespace App\Modules\Appeal\Models;

use App\Models\MyModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppealTypeModel extends MyModel
{
    use SoftDeletes;

    protected $fillable = ['label', 'description', 'public'];

    protected $casts = [
        'public' => 'boolean'
    ];


    public function appeals()
    {
        return $this->hasMany(AppealModel::class, 'appeal_type_id', 'id');
    }
}
