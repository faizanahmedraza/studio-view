<?php

namespace App\Http\Requests\Api;

class StoreUserRequest extends Request
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
            'first_name'        => 'required|max:32',
            'last_name'         => 'required|max:32',
            'email'      => 'required|email|max:255|unique:users,email,NULL,id,deleted_at,NULL,role_id,1',
            'phone'             => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'password'          => 'required|min:6|max:30',
            // 'confirm_password'  => 'required|string|min:6|same:password|max:30',
            'device_token'          => 'required',
            'device_type'          => 'required',
            // 'role_id'          => 'required',
            'profile_picture'   => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ];
    }

    // public function all($keys = null)
    // {
    //     $data = parent::all($keys);

    //     if (array_key_exists('phone', $data)) {
    //         $data['phone'] = sprintf('+1%s', ltrim($data['phone'], '+1'));
    //     }

    //     $this->merge($data); // This is required since without merging, it doesn't pass modified value to controller.

    //     return $data;
    // }
}
