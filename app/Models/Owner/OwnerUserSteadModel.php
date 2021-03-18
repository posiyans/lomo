<?php

namespace App\Models\Owner;

use App\Models\MyModel;
use App\Models\Stead;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OwnerUserSteadModel extends MyModel
{
    use HasFactory;

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
