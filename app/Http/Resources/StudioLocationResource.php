<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudioLocationResource extends JsonResource
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
            'address' => $this->address ?? '',
            'street' => $this->street ?? '',
            'country' => $this->country ?? '',
            'city' => $this->city ?? '',
            'state' => $this->state ?? '',
            'zip_code' => $this->zip_code ?? '',
            'lat' => $this->lat ?? '',
            'lng' => $this->lng ?? '',
            'additional_details' => $this->additional_details ?? '',
        ];
    }
}
