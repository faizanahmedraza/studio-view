<?php

namespace App\Events\Api;


use Illuminate\Queue\SerializesModels;

class NewUserCreateEvent
{
    use SerializesModels;

    public $user;

    public $code;

    /**
     * Create a new event instance for attendance user signup api.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
