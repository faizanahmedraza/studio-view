<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Null_;

class StudioResource extends JsonResource
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
            'name' => $this->name ?? '',
            'detail' => $this->detail ?? '',
            'minimum_booking_hr' => $this->minimum_booking_hr ?? '',
            'max_occupancy_people' => $this->max_occupancy_people ?? '',
            'hours_status' => $this->hours_status ?? '',
            'hrs_from' => $this->hrs_from ?? '',
            'hrs_to' => $this->hrs_to ?? '',
            'adv_booking_time_id' => $this->adv_booking_time_id ?? '',
            'past_client' => $this->past_client ?? '',
            'audio_sample' => $this->audio_sample ?? '',
            'amenities' => $this->amenities ?? '',
            'main_equipment' => $this->main_equipment ?? '',
            'rules' => $this->rules ?? '',
            'cancelation_policy' => $this->cancelation_policy ?? '',
            'location'=>new StudioLocationResource($this->getLocation),
            'price'=>new StudioPriceResource($this->getPrice),
            'types'=> StudioTypeResource::collection($this->getStudioTypes),
            'images'=> StudioImageResource::collection($this->getImages),
            'user' => new UserResource($this->user),
            'status' => $this->status ?true :false ,
            'is_premium' => empty($this->paidPromotion) ? false : true,
            'ratings' =>  $this->avg_ratings ?? 0,
            'approved_at' => $this->approved_at ?? '',
            'created_at' => $this->created_at ?? '',
        ];
    }
}
