<?php

namespace App;

use App\Models\Stead;

use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Traits\HasRoles;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable implements MustVerifyEmail
{
    use LaratrustUserTrait;
    use Notifiable;
//    use HasRoles;

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

    /**
     * Получить log модели
     */
    public function log()
    {
        return $this->morphMany('App\Models\Log', 'commentable');
    }

    public function accounts()
    {
        return $this->hasMany('App\LinkedSocialAccount');
    }

    public function steads()
    {
        return $this->hasMany('App\Models\Stead', 'user_id', 'id');
    }

    public function fullName()
    {
        return $this->last_name.' '.$this->name.' '.$this->middle_name;
    }

    public function fName()
    {
        $fio = '';
        if (!empty($this->last_name)) {
            $fio .= $this->last_name . ' ';
        }
        if (!empty($this->name)) {
            $fio .= mb_substr($this->name, 0, 1).'.';
        }
        if (!empty($this->middle_name)) {
            $fio .= mb_substr($this->middle_name, 0, 1).'.';
        }
        return $fio;
    }

    public function syncSteads($steads)
    {
        $userSteads = $this->steads;
        foreach ($userSteads as $userStead) {
            if (in_array($userStead->id, $steads)){
                unset($steads[$userStead->id]);
            } else {
                $userStead->user_id = null;
                $userStead->save();
            }
        }
        foreach ($steads as $stead) {
            $steadModel = Stead::find($stead);
            if ($steadModel){
                $steadModel->user_id = $this->id;
                $steadModel->save();
            }
        }
    }


//    public function sendPasswordResetNotification($token)
//    {
//        $user = User::find(6);
//        Mail::send('emails.reset_password', ['user' => $user], function ($m) use ($user) {
//            $m->to($user->email, $user->name)->subject('Your Password Reset!');
//        });
//    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }
}
