<?php

namespace App\Http\Requests\Api;

class RequestStudioStatusRequest extends Request
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
            'booking_id' =>'required|exists:studio_bookings,id',
            'status' =>"required|in:0,1,2",
        ];
    }

    public function messages()
    {
        return [
          'end_time.after' => 'The end time must be a time after start time.'
        ];
    }
}
