<?php

namespace App\Http\Requests\Api;

class UserLoginGoogleRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'providerData' => 'required|array',
            'idToken' => 'required',
            "device_token" => 'required',
            "device_type" => 'required',
        ];
    }

}
