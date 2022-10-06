<?php

namespace App\Http\Resources\LostDogs;

use App\Models\DogListingImages;
use Illuminate\Http\Resources\Json\JsonResource;

class DogsLostResource extends JsonResource
{
    /**
     * Transform the Dog collection resource into an array used in lost dogs
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
            'dog_id'         => $this->id,
            'title'          => $this->title,
            'name'           => $this->name,
            'description'    => $this->description,
            'image'          => $this->image,
            'cover_image'    => $this->getCoverImagePath(),
            'listing_images' => $listingsImages,
            'size'           => $this->size,
            'gender'         => $this->gender,
            'lost_date'      => $this->lostDog->lost_at ?? "",
            "lost_city"      => $this->city->name ?? "",
            'lost_at'        => $this->name ?? "",
            "reward"         => $this->reward ?? 0,
            "owner"          => $this->user->combineName(),
        ];
    }
}
