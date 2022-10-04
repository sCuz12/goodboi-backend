<?php

namespace App\Http\Resources\LostDogs;

use App\Models\DogListingImages;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class LostDogSingleResource extends JsonResource
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


        return [
            'id'                => $this->id,
            'title'             => $this->title,
            'name'              => $this->name,
            'description'       => $this->description,
            'image'             => $this->image,
            'lost_date'         => Carbon::parse($this->lostDog->lost_at),
            'cover_image'       => $this->getCoverImagePath(),
            'listing_images'    => $listingsImages,
            'vaccinations'      => $vaccinations ?? null,
            'city'              => $this->city->name,
            'gender'            => $this->gender,
            'owner'             => $this->user->combineName(),
        ];
    }
}
