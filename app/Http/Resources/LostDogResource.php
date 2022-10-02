<?php

namespace App\Http\Resources;

use App\Models\DogListingImages;
use Carbon\Carbon;
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


        $data = [
            'id'             => $this->id,
            'title'          => $this->title,
            'name'           => $this->name,
            'description'    => $this->description,
            'image'          => $this->image,
            'cover_image'    => $this->getCoverImagePath(),
            'listing_images' => $listingsImages,
            'size'           => $this->size,
            'gender'         => $this->gender,
            'lost_date'      => $this->lostDogs->lost_at ?? "",
            "lost_city"      => $this->lostDogs->location->city->name ?? "",
            'lost_at'        => $this->lostDogs->location->name ?? "",
            "reward"         => $this->lostDogs->reward ?? 0,
        ];

        return $data;
    }
}
