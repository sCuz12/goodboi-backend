<?php

namespace App\Http\Resources;

use App\Models\DogListingImages;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DogEditSingleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $listingsImages = DogListingImages::with('dogs')->where('dog_id', $this->id)->pluck('url')->toArray();

        // TODO : This is required? This will slow performance ?
        $listingsImages = array_map(function ($image) {
            return asset($image);
        }, $listingsImages);

        $vaccinations       = $this->healthBook->vaccinations->pluck('name');
        $vaccinations_id    = $this->healthBook->vaccinations->pluck('id');
        $shelterInformation = new ShelterResource($this->Shelter);


        return [
            'id'                => $this->id,
            'title'             => $this->title,
            'name'              => $this->name,
            'description'       => $this->description,
            'cover_image'       => $this->getCoverImagePath(),
            'vaccinations_id'   => $vaccinations_id ?? null,
            'size'              => $this->size,
            'country_id'        => $this->city->country->id,
            'city_id'           => $this->city->id,
            'dob'               => $this->dob,
            'gender'            => $this->gender

        ];
    }
}
