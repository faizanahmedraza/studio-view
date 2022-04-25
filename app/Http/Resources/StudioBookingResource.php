<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudioBookingResource extends JsonResource
{

    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id ?? '',
            'studio' => $this->studio->name ?? '',
            'date' => $this->date ?? '',
            'start_time' => $this->start_time ?? '',
            'end_time' => $this->end_time ?? '',
            'hourly_rate' => $this->hourly_rate ?? '',
            'audio_eng_included' => $this->audio_eng_included ? true :false,
            'discount' => $this->discount ?? '',
            'audio_eng_rate_hr' => $this->audio_eng_rate_hr ?? '',
            'audio_eng_discount' => $this->audio_eng_discount ? true :false,
            'other_fees' => $this->other_fees ?? '',
            'mixing_services' => $this->mixing_services ?? '',
            'total_hours_price' => $this->total_hours_price ?? '',
            'total_eng_hours_price' => $this->total_eng_hours_price ?? '',
            'grand_total' => $this->grand_total ?? '',
            'on_arrival_to_bring_issued_id_agree' => $this->on_arrival_to_bring_issued_id_agree ?? '',
            'studio_cancellation_policy_agree' => $this->studio_cancellation_policy_agree ?? '',
            'status' => $this->status ? true :false,
            'created_at' => $this->created_at ?? '',
        ];
    }
}
