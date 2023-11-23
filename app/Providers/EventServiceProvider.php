<?php

namespace App\Providers;

use App\Models\Owner\OwnerUserModel;
use App\Models\Owner\OwnerUserSteadModel;
use App\Models\Owner\OwnerUserValueModel;
use App\Modules\Auth\Events\PasswordReset;
use App\Modules\Auth\Listeners\ChangePasswordNotification;
use App\Observers\OwnerUserObserver;
use App\Observers\OwnerUserSteadObserver;
use App\Observers\OwnerUserValueObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
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
