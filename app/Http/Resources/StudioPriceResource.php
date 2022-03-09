<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudioPriceResource extends JsonResource
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
            'hourly_rate' => $this->hourly_rate ?? '',
            'audio_eng_included' => $this->audio_eng_included ? true : false,
            'discount' => $this->discount ?? '',
            'audio_eng_rate_hr' => $this->audio_eng_rate_hr ?? '',
            'audio_eng_discount' => $this->audio_eng_discount ? true : false,
            'other_fees' => $this->other_fees ?? '',
            'mixing_services' => $this->mixing_services ?? '',
        ];
    }
}
