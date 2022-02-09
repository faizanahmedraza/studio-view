<?php

namespace App\Http\Requests\Api;

class StoreAttendacneUserRequest extends Request
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

    public function messages()
    {
        return [

            'profile_picture.image'  => 'Profile image must be an image',
            'profile_picture.mimes'  => 'The profile image  must be a file of type: jpeg, png, jpg, gif.',

        ];
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'full_name'        => 'required|max:32',
            'email'             => 'required|email|unique:users,email,NULL,id,deleted_at,NULL,role_id,1',
            'password'          => 'required|min:6|max:30',
            'device_token'          => 'required',
            'device_type'          => 'required',
            'profile_picture'   => 'image|mimes:jpeg,png,jpg,gif',
        ];
    }

}
