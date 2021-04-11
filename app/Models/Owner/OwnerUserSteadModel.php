<?php

namespace App\Models\Owner;

use App\Models\MyModel;
use App\Models\Stead;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OwnerUserSteadModel extends MyModel
{
    use HasFactory, SoftDeletes;

    public function ownerFullName()
    {
        return $this->owner->fullName();
    }

    public function owner()
    {
        return $this->hasOne(OwnerUserModel::class, 'uid', 'owner_uid');
    }

    public function stead()
    {
        return $this->hasOne(Stead::class, 'id', 'stead_id');
    }
}
