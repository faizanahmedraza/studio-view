<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewUserResource extends JsonResource
{
    private $_token = '';

    public function __construct($resource, $_token = '')
    {
        parent::__construct($resource);
        if (empty($_token)) {
            $this->_token = request()->bearerToken();
        } else {
            $this->_token = $_token;
        }
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'full_name' => $this->getFullname(),
            'phone' => $this->phone,
            'email' => $this->email,
            // 'role_id' => $this->role_id,
            // 'email_verified' => $this->email_verified,
            'sms_verified' => $this->sms_verified,
            // 'is_verified' => $this->is_verified,
            // 'is_unblock' => $this->is_unblock,
            // 'is_notification' => $this->is_notification,
            'is_active' => $this->is_active,
            'profile_picture' => $this->profile_picture,
            'is_fb' => $this->is_fb,
            'is_google' => $this->is_google,
            // 'email_verified_at' => $this->email_verified_at ?? '',
            '_token' => $this->_token,

        ];
    }
}
