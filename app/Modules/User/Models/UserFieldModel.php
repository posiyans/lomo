<?php

namespace App\Modules\User\Models;

use App\Models\MyModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFieldModel extends MyModel
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'value' => 'array'
    ];


}
