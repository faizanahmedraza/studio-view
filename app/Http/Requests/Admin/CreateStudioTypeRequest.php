<?php

namespace App\Http\Requests\Admin;

class CreateStudioTypeRequest extends Request
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
            // description
            'type_name'=>'required|string|max:255',
            // image
            'image'=> 'sometimes|nullable|mimes:jpg,gif,png|max:10000',
        ];
    }

}
