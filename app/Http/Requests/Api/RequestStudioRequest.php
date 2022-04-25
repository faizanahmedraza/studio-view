<?php

namespace App\Http\Requests\Api;

class RequestStudioRequest extends Request
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
        $date=date('Y-m-d');

        return [
            'studio_id' =>'required|exists:studios,id',
            'date' =>"required|date_format:Y-m-d|after_or_equal:$date",
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'audio_engineer' => 'nullable|boolean',
            'mixing_services' => 'nullable|boolean',
            'other_services' => 'nullable|boolean',
            'on_arrival_to_bring_issued_id_agree' => 'required|boolean',
            'studio_cancellation_policy_agree' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
          'end_time.after' => 'The end time must be a time after start time.'
        ];
    }
}
