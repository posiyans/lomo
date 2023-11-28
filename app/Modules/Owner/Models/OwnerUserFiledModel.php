<?php

namespace App\Modules\Owner\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OwnerUserFiledModel extends Model
{
    use HasFactory;
    use SoftDeletes;
}
