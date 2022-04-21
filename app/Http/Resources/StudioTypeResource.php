<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudioTypeResource extends JsonResource
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
            'id' => $this->type_id ?? '',
            'name' => $this->getType->name ?? '',
            'image_url' => $this->getType->image_url ?? ''
        ];
    }
}
