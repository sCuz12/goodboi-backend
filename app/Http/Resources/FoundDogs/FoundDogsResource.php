<?php

namespace App\Http\Resources\FoundDogs;

use App\Models\DogListingImages;
use App\Models\Location;
use Illuminate\Http\Resources\Json\JsonResource;

class FoundDogsResource extends JsonResource
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

        //location
        $location = Location::find($this->location_id);

        $data = [
            'id'             => $this->id,
            'title'          => $this->title,
            'name'           => $this->name,
            'description'    => $this->description,
            'image'          => $this->image,
            'listing_type'   => $this->listing_type,
            'cover_image'    => $this->getCoverImagePath(),
            'listing_images' => $listingsImages,
            'size'           => $this->size,
            'gender'         => $this->gender,
            'found_date'     => $this->foundDog->found_date ?? "",
            "found_city"     => $this->foundDog->location->city->name ?? "",
            'found_at'       => $this->foundDog->location->name ?? "",
            "founder"        => $this->user ? $this->user->combineName()  : "",
        ];

        return $data;
    }
}
