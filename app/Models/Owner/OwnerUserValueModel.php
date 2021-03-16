<?php

namespace App\Models\Owner;

use App\Models\MyModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OwnerUserValueModel extends MyModel
{
    use HasFactory;
    protected $casts = [
        'value' => 'encrypted',
    ];
    protected $fillable = ['uid', 'property'];
}
