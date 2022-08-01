<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LatestStudioBookingResource extends JsonResource
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
            'booking_id' => $this->id ?? '',
            'studio_id' => $this->studio_id ?? '',
            'user_id' => $this->user_id ?? '',
            'status' => $this->status,
            'created_at' => $this->created_at ?? '',
        ];
    }
}
