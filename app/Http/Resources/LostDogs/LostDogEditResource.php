<?php

namespace App\Http\Resources\LostDogs;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class LostDogEditResource extends JsonResource
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
            'lost_at'           => $this->lostDog->location->id ?? "",
            'gender'            => $this->gender,
            "reward"            => $this->lostDog->reward ?? 0,
            'size'              => $this->size,
            'lost_date'         => Carbon::parse($this->lostDog->lost_at)->format('d/m/Y'),
        ];
    }
}
