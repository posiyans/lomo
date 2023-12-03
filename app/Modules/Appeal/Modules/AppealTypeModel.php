<?php

namespace App\Modules\Appeal\Modules;

use App\Models\MyModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppealTypeModel extends MyModel
{
    use SoftDeletes;

    protected $fillable = ['label'];

}
