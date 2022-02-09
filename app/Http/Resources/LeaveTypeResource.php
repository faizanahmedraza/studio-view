<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaveTypeResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id??'',
            'type' => $this->type??'',
            'counts' => $this->counts??'',
        ];
    }
}
