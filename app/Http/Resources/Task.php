<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Task extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        
        return [
            'id' => $this->id,
            'date' => $this->date,
            'state' => $this->state,
            'fips' => $this->fips,
            'cases' => $this->cases,
            'deaths' => $this->deaths,
            'state' => $this->state,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),
        ];

    }
}
