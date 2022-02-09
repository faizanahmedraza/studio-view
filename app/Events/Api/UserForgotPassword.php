<?php

namespace App\Events\Api;


use Illuminate\Queue\SerializesModels;

class UserForgotPassword
{
    use SerializesModels;

    public $user;

    public $pass;

    /**
     * Create a new event instance for forgot password.
     *
     * @return void
     */
    public function __construct($user, $pass)
    {
        $this->user = $user;
        $this->pass = $pass;
    }
}
