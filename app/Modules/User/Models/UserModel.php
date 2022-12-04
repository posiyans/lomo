<?php

namespace App\Modules\User\Models;

use App\Models\User;
use App\Modules\User\Repositories\GetUidForUserRepository;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Sanctum\HasApiTokens;

class UserModel extends Authenticatable implements MustVerifyEmail
{
    use LaratrustUserTrait;
    use Notifiable;
    use HasApiTokens;


    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'last_name', 'middle_name', 'phone', 'adres', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'consent_to_email' => 'boolean',
        'consent_personal' => 'boolean',
        'steads_id' => 'array'
    ];

    public function getUidAttribute() {
        try {
            $uid = (new GetUidForUserRepository($this))->run();
            return $uid;
        } catch (\Exception $e) {
            return null;
        }
    }
}
