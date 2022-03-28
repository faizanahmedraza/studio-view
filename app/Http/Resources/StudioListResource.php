<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudioListResource extends JsonResource
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
            'price'=>new StudioPriceResource($this->getPrice),
            'types'=> StudioTypeResource::collection($this->getStudioTypes),
            'images'=> StudioImageResource::collection($this->getImages),
            'location'=>new StudioLocationResource($this->getLocation),
            'approved_at' => $this->approved_at ?? '',
            'created_at' => $this->created_at ?? '',
        ];
    }
}
