<?php

namespace App\Http\Resources\FoundDogs;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class EditFoundDogResource extends JsonResource
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
            'id'                => $this->id,
            'title'             => $this->title,
            'name'              => $this->name,
            'description'       => $this->description,
            'country_id'        => $this->city->country->id,
            'city_id'           => $this->city->id,
            'location_id'       => $this->foundDog->location->id ?? "",
            'gender'            => $this->gender,
            'size'              => $this->size,
            'found_date'        => Carbon::parse($this->foundDog->found_at)->format('d/m/Y'),
            'listing_type'      => $this->listing_type,
        ];
    }
}
