<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubAdminRequest extends FormRequest
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
            'first_name.required' => 'Full name is required',
            'phone.required' => 'Please enter the correct phone-number',
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
            'email' => 'required|email|max:255|unique:users,email,NULL,id,deleted_at,NULL,role_id,2',
            'phone' => 'required|max:24|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            "rights.*" => "required|string",
            "permissions.*" => "required|string",
            "address" => "",
            'password' => 'min:8|required|max:30',
            'confirm_password' => 'string|min:8|same:password|max:30',
        ];
    }
}
