<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShelterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id'            => $this->id,
            'shelter_name'  => $this->shelter_name,
            'address'       => $this->address,
            'description'   => $this->description,
            'cover_image'   => $this->getCoverImagePath(),
            'city'          => $this->city->name,
            'email'         => $this->user->email,
            'phone'         => $this->phone,
        ];
    }
}
