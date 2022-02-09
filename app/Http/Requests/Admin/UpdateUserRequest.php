<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'profile_picture.mimes'  => 'The profile image must be a file of type: jpeg, png, jpg, gif.',

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
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'phone' => 'required|max:24|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'password' => 'nullable|min:6|max:30'
        ];
    }
}
