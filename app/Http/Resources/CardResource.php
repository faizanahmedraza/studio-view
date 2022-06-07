<?php

namespace App\Http\Resources;

use App\Models\Notification;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
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

            'id' => $this->id,
            'card_id' => $this->card_id,
            'last_digits' => $this->last_digits,
            'exp_month' => $this->exp_month,
            'exp_year' => $this->exp_year,
            'brand' => $this->brand,
            'country' => $this->country,
            'holder_name' => $this->holder_name,
        ];
    }
}
