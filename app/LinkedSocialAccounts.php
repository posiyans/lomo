<?php

namespace App;



class LinkedSocialAccounts extends MyModel
{
    protected $fillable = ['provider_name', 'provider_id' ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
