<?php

namespace App\Models\Socials;

use App\Models\MyModel;

class LinkedSocialAccounts extends MyModel
{
    protected $fillable = ['provider_name', 'provider_id' ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
