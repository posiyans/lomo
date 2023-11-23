<?php

namespace App\Models;

use App\Modules\User\Models\UserModel;
use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $last_name
 * @property string|null $middle_name
 * @property string|null $avatar
 * @property array|null $steads_id
 * @property string|null $adres
 * @property string|null $phone
 * @property bool $consent_to_email
 * @property bool $consent_personal
 * @property string|null $last_connect
 * @property string $uid uid пользователя
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Social\Models\LinkedSocialAccounts> $accounts
 * @property-read int|null $accounts_count
 * @property-read \App\Modules\BanUser\Models\BanUserModel|null $ban
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Laratrust\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Laratrust\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Stead> $steads
 * @property-read int|null $steads_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel orWherePermissionIs($permission = '')
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel orWhereRoleIs($role = '', $team = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAdres($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereConsentPersonal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereConsentToEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereDoesntHavePermission()
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereDoesntHaveRole()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastConnect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel wherePermissionIs($permission = '', $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereRoleIs($role = '', $team = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSteadsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Social\Models\LinkedSocialAccounts> $accounts
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Log> $log
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Laratrust\Permission> $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Laratrust\Role> $roles
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Stead> $steads
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Social\Models\LinkedSocialAccounts> $accounts
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Log> $log
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Laratrust\Permission> $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Laratrust\Role> $roles
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Stead> $steads
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Social\Models\LinkedSocialAccounts> $accounts
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Log> $log
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Laratrust\Permission> $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Laratrust\Role> $roles
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Stead> $steads
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Social\Models\LinkedSocialAccounts> $accounts
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Log> $log
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Laratrust\Permission> $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Laratrust\Role> $roles
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Stead> $steads
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @mixin \Eloquent
 */
class User extends UserModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_name',
        'middle_name',
        'phone',
        'adres',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
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
        return $this->hasMany('App\Modules\Social\Models\LinkedSocialAccounts', 'user_id', 'id');
    }

    public function steads()
    {
        return $this->hasMany('App\Models\Stead', 'user_id', 'id');
    }

    public function fullName()
    {
        return $this->last_name . ' ' . $this->name . ' ' . $this->middle_name;
    }

    public function smallName()
    {
        return $this->last_name . ' ' . $this->name;
    }

    public function getSocialList()
    {
        $soc = $this->accounts;
        $data = [];
        foreach ($soc as $item) {
            $data[] = $item->provider_name;
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
            $fio .= mb_substr($this->name, 0, 1) . '.';
        }
        if (!empty($this->middle_name)) {
            $fio .= mb_substr($this->middle_name, 0, 1) . '.';
        }
        return $fio;
    }

    public function syncSteads($steads)
    {
        $userSteads = $this->steads;
        foreach ($userSteads as $userStead) {
            if (in_array($userStead->id, $steads)) {
                unset($steads[$userStead->id]);
            } else {
                $userStead->user_id = null;
                $userStead->save();
            }
        }
        foreach ($steads as $stead) {
            $steadModel = Stead::find($stead);
            if ($steadModel) {
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
     * @param  null  $description
     * @param  null  $stead
     * @param  array  $options
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
