<?php

namespace App\Http\Resources;

use App\Models\DogListingImages;
use Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class DogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {


        $data = [
            'id'           => $this->id,
            'title'        => $this->title,
            'name'         => $this->name,
            'description'  => $this->description,
            'cover_image'  => $this->getCoverImagePath(),
            'age'          => Carbon::parse($this->dob)->age,
            'city'         => $this->city->name ?? null,
            'size'         => $this->size,
            'slug'         => $this->slug,
            'total_views'  => $this->total_views,
            'gender'       => $this->gender,
            'listing_type' => $this->listing_type,
            'shelter'      => $this->shelter->shelter_name ?? "",
        ];

        if (auth('api')->user()) {
            $data += [
                'is_favourited' => $this->isFavoritedByExist(auth('api')->user()->id) ?? false,
            ];
        }
        return $data;
    }
}
