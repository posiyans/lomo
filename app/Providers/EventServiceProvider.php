<?php

namespace App\Providers;

use App\Listeners\LogNotification;
use App\Modules\Auth\Events\PasswordReset;
use App\Modules\Auth\Listeners\ChangePasswordNotification;
use App\Modules\Auth\Listeners\SendEmailVerificationNotification;
use App\Modules\Owner\Models\OwnerUserModel;
use App\Modules\Owner\Models\OwnerUserSteadModel;
use App\Modules\Owner\Models\OwnerUserValueModel;
use App\Observers\OwnerUserObserver;
use App\Observers\OwnerUserSteadObserver;
use App\Observers\OwnerUserValueObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Notifications\Events\NotificationSent;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        NotificationSent::class => [
            LogNotification::class
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
//        MessageSending::class => [
//            SendMail::class,
//        ],
        PasswordReset::class => [
            ChangePasswordNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        OwnerUserModel::observe(OwnerUserObserver::class);
        OwnerUserSteadModel::observe(OwnerUserSteadObserver::class);
        OwnerUserValueModel::observe(OwnerUserValueObserver::class);
    }
}
