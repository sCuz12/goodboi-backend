<?php

namespace App\Http\Resources;

use App\Models\DogListingImages;
use App\Models\Location;
use Illuminate\Http\Resources\Json\JsonResource;

class LostDogResource extends JsonResource
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
            'dog_id'         => $this->dog_id,
            'title'          => $this->title,
            'name'           => $this->name,
            'description'    => $this->description,
            'listing_type'   => $this->listing_type,
            'image'          => $this->image,
            'cover_image'    => $this->getCoverImagePath(),
            'listing_images' => $listingsImages,
            'size'           => $this->size,
            'gender'         => $this->gender,
            'lost_date'      => $this->lost_at ?? "",
            "lost_city"      => $location->city->name ?? "",
            'lost_at'        => $location->name ?? "",
            "reward"         => $this->reward ?? 0,
            "owner"          => $this->user->combineName() ?? "",
        ];

        return $data;
    }
}
