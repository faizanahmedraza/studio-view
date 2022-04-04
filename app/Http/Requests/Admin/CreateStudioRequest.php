<?php

namespace App\Http\Requests\Admin;

use App\Models\User;

class CreateStudioRequest extends Request
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
            'customer'=>'required|in:'.implode(',',User::where('role_id',1)->pluck('id')->toArray()),
            'studio_name'=>'required|string|max:255',
            'details'=>'required|string',
            'studio_types'=>'required|array',
            'studio_types.*' => 'required|numeric|distinct|exists:std_types,id|min:1',
            'minimum_booking_hr' => 'required|numeric',
            'max_occupancy_people' => 'required|numeric',
            'hours_status' => 'required|numeric|in:1,2,3',
            "hrs_to" => "required_if:hours_status,==,3",
            "hrs_from" => "required_if:hours_status,==,3",
            "adv_booking_time" =>'required|numeric|exists:adv_booking_times,id',
            'past_client'=>'nullable|string',
            'audio_samples'=>'nullable|string',
            // feature
            'amenities'=>'required|string',
            'main_equipment'=>'nullable|string',
            // rule
            'rules'=>'nullable|string',
            'cancellation_policy'=>'nullable|string',

            // photos
            'images'=> ($this->isMethod('put')) ? 'sometimes|nullable|array' : 'required|array|min:1',
            'images.*' => ($this->isMethod('put')) ? 'nullable|mimes:jpg,gif,png|max:10000' : 'required|mimes:jpg,gif,png|max:10000',

            // location
            "address"=>'required|string',
            "lat"=>'required',
            "lng"=>'required',
            "additional_location_details"=>'nullable|string',

            // pricing
            'hourly_rate'=>'required',
            'audio_eng_included'=>'nullable|boolean',
            "discount"=>'nullable|between:0,100',
            "additional_services"=>'nullable|array',

        ];
    }

}
