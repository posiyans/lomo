<?php

namespace App\Modules\User\Models;

use App\Models\MyModel;
use App\Modules\Auth\Notifications\VerifyEmail;
use App\Modules\BanUser\Models\BanUserModel;
use App\Modules\Owner\Models\OwnerUserModel;
use App\Modules\User\Factories\UserModelFactory;
use App\Notifications\ResetPassword;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Modules\User\Models\UserModel
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
 * @property-read BanUserModel|null $ban
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Laratrust\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Laratrust\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel orWherePermissionIs($permission = '')
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel orWhereRoleIs($role = '', $team = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereAdres($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereConsentPersonal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereConsentToEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereDoesntHavePermission()
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereDoesntHaveRole()
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereLastConnect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel wherePermissionIs($permission = '', $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereRoleIs($role = '', $team = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereSteadsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserModel extends MyModel implements MustVerifyEmail,
                                           AuthenticatableContract,
                                           AuthorizableContract,
                                           CanResetPasswordContract
{
    use LaratrustUserTrait;
    use Notifiable;
    use HasApiTokens;
    use HasFactory;
    use Authenticatable, Authorizable, CanResetPassword, \Illuminate\Auth\MustVerifyEmail;

    public static $no_email_prefix = 'no_email_';


    protected static function newFactory(): Factory
    {
        return UserModelFactory::new();
    }

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
//        'phone',
        'uid',
//        'adres',
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
        'options' => 'array',
    ];


    public function ban()
    {
        return $this->hasOne(BanUserModel::class);
    }


    /**
     * пользователь собствеенник
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function owner()
    {
        return $this->hasOne(OwnerUserModel::class, 'user_uid', 'uid');
    }


    public function routeNotificationForMail($notification)
    {
        // Вернуть только адрес электронной почты ...
//        return $this->email_address;

        // Вернуть адрес электронной почты и имя ...
        return [$this->email => $this->fullName()];
    }

    /**
     * Отправьте уведомление о подтверждении по электронной почте.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * Отправьте уведомление о сбросе пароля.
     *
     * @param $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * при смене почты сбрасываем флаг подтвержждения ее
     *
     * @param $value
     * @return void
     */
    public function setEmailAttribute($value)
    {
        $this->email_verified_at = null;
        $this->attributes['email'] = strtolower(trim($value));
    }

    public function getEmailAttribute()
    {
//        return strlen(self::$no_email_prefix);
        if (mb_substr($this->attributes['email'], 0, strlen(self::$no_email_prefix)) == self::$no_email_prefix) {
            return '';
        }
        return $this->attributes['email'];
    }

    public function fullName(): string
    {
        return $this->last_name . ' ' . $this->name . ' ' . $this->middle_name;
    }

    public function smallName()
    {
        return $this->last_name . ' ' . $this->name;
    }


    public function routeNotificationForTelegram()
    {
        return $this->options['telegram'];
    }
}
