<?php

namespace App\Providers;


use App\Events\Api\ResendCodeEvent;
use App\Events\Api\UserCreateEvent;
use App\Listeners\Api\ForgotPassword;
use App\Events\Api\NewUserCreateEvent;

use App\Events\Api\UserForgotPassword;
use Illuminate\Auth\Events\Registered;
use App\Listeners\Api\ResendCodeListener;
use App\Listeners\Api\UserCreateListener;
use App\Listeners\Api\NewUserCreateListener;
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
        UserForgotPassword::class => [
            ForgotPassword::class,
        ],
        UserCreateEvent::class => [
            UserCreateListener::class,
        ],
        ResendCodeEvent::class => [
            ResendCodeListener::class,
        ],
        NewUserCreateEvent::class => [
            NewUserCreateListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
