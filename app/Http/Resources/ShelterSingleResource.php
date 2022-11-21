<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShelterSingleResource extends JsonResource
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
            'id'           => $this->id,
            'shelter_name' => $this->shelter_name,
            'address'      => $this->address,
            'phone'        => $this->phone,
            'description'  => $this->description,
            'city'         => $this->city->name ?? "",
            'cover_image'  => $this->user->getProfileImagePath(),
            'email'        => $this->user->email,
            'instagram'    => $this->instagram ?? "",
            'facebook'     => $this->facebook,
            'city_id'      => $this->city_id,
        ];
    }
}
