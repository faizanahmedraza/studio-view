<?php

namespace App\Listeners\Api;

use App\Events\Api\UserCreateEvent;
use App\Notifications\Api\UserCreateNotification;

class UserCreateListener
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

    public function handle(UserCreateEvent $event)
    {
        $user = $event->user;
        $code = $event->code;
        $user->notify(new UserCreateNotification($code));
    }
}
