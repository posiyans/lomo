<?php

namespace App\Models\Owner;

use App\Models\MyModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OwnerUserValueModel extends MyModel
{
    use HasFactory , SoftDeletes;
    protected $casts = [
        'value' => 'encrypted',
    ];
    protected $fillable = ['uid', 'property'];
}
