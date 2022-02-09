<?php

namespace App\Listeners\Api;

use App\Events\Api\NewUserCreateEvent;
use App\Events\Api\AttendanceUserCreateEvent;
use App\Notifications\Api\NewUserCreateNotification;
use App\Notifications\Api\AttendanceUserCreateNotification;

class NewUserCreateListener
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

    public function handle(NewUserCreateEvent $event)
    {
        $user = $event->user;
        $code = $event->code;
        $user->notify(new NewUserCreateNotification($code));
    }
}
