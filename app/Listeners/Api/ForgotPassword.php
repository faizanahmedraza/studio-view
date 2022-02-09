<?php

namespace App\Listeners\Api;


use App\Events\UserForgotPassword;

use App\Notifications\ForgotPasswordNotification;

class ForgotPassword
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle(UserForgotPassword $event)
    {
        $user = $event->user;
        $pass = $event->pass;
        $user->notify(new ForgotPasswordNotification($pass));
    }
}
