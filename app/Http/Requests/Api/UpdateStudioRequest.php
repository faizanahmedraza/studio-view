<?php

namespace App\Http\Requests\Api;

class UpdateStudioRequest extends Request
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
            'name'=>'required|string|max:255',
            'detail'=>'required|string',
            'types'=>'required|array',
            'types.*' => 'required|numeric|distinct|exists:std_types,id|min:1',
            'minimum_booking_hr' => 'required|numeric',
            'max_occupancy_people' => 'required|numeric',
            'hours_status' => 'required|numeric|in:1,2,3',
            "hrs_to" => "required_if:hours_status,==,3",
            "hrs_from" => "required_if:hours_status,==,3",
            "adv_booking_time_id" =>'required|numeric|exists:adv_booking_times,id',
            'past_client'=>'nullable|string',
            'audio_sample'=>'nullable|string',
            // feature
            'amenities'=>'required|string',
            'main_equipment'=>'nullable|string',
            // rule
            'rules'=>'nullable|string',
            'cancelation_policy'=>'nullable|string',

            // photos
            'photos'=>'required|array|min:1',
            'photos.old' => 'required|array|min:1',
            'photos.new'=>'nullable|array',
            'photos.new.*' => 'nullable|base64image',

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
