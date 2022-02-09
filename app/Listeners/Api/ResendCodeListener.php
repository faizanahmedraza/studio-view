<?php

namespace App\Listeners\Api;

use App\Events\Api\ResendCodeEvent;

use App\Notifications\Api\ResendCodeNotification;

class ResendCodeListener
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

    public function handle(ResendCodeEvent $event)
    {
        $user = $event->user;
        $code = $event->code;
        $user->notify(new ResendCodeNotification($code));
    }
}
