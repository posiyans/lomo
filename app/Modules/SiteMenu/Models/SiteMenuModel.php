<?php

namespace App\Modules\SiteMenu\Models;

use App\Models\MyModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiteMenuModel extends MyModel
{
    use HasFactory;

    protected $fillable = ['label', 'icon', 'path'];
}
