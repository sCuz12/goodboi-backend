<?php

namespace App\Http\Resources;

use App\Models\DogListingImages;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DogSingleResource extends JsonResource
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
        $shelterInformation = new ShelterResource($this->Shelter);


        return [
            'id' => $this->id,
            'title' => $this->title,
            'name' => $this->name,
            'description' => $this->description,
            'image'       => $this->image,
            'age'         => Carbon::parse($this->dob)->age,
            'cover_image' => $this->getCoverImagePath(),
            'listing_images' => $listingsImages,
            'vaccinations'  => $vaccinations ?? null,
            'size'          => $this->size,
            'city'         => $this->city->name,
            'shelter_info' => $shelterInformation,
            'total_views'  => $this->total_views,
            'total_favourites' => $this->getCountOfFavourites(),
            'facebook_page' => $this->shelter->facebook_pagename,
            'gender'        => $this->gender
        ];
    }
}
