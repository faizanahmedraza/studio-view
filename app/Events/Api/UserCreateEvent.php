<?php

namespace App\Events\Api;


use Illuminate\Queue\SerializesModels;

class UserCreateEvent
{
    use SerializesModels;

    public $user;

    public $code;

    /**
     * Create a new event instance for create new user.
     *
     * @return void
     */
    public function __construct($user, $code)
    {
        $this->user = $user;
        $this->code = $code;
    }
}
