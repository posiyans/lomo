<?php

namespace App\Models;

use App\Models\Log;
use App\Models\Stead;

use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Mail;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use LaratrustUserTrait;
    use Notifiable;
    use HasApiTokens;

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
        return $this->hasMany('App\Models\Socials\LinkedSocialAccounts','user_id', 'id' );
    }

    public function steads()
    {
        return $this->hasMany('App\Models\Stead', 'user_id', 'id');
    }

    public function fullName()
    {
        return $this->last_name.' '.$this->name.' '.$this->middle_name;
    }

    public function smallName()
    {
        return $this->last_name.' '.$this->name;
    }
    public function getSocialList() {
           $soc = $this->accounts;
           $data = [];
        foreach ($soc as $item) {
            $data[] =$item->provider_name;
           }
        return $data;
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

    public function changeEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // todo log смена email!!!
            $this->email = $email;
            $this->email_verified_at = null;
            $this->save();
            $this->sendEmailVerificationNotification();
            return true;
        }
        return false;
    }


    /**
     * сохранить изменения и добавть логи
     *
     * @param null $description
     * @param null $stead
     * @param array $options
     * @return bool
     */
    public function logAndSave($description = null, $stead = null, array $options = [])
    {
        $original_model = $this->getOriginal();
        if ($this->save($options)) {
            return Log::addLog($this, $original_model, $description, $stead);
            return true;
        }
        return false;
    }
}
