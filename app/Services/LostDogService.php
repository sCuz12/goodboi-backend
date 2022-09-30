<?php

namespace App\Services;

use App\Enums\DogListingStatuses;
use App\Enums\ListingTypesEnum;
use App\Models\Dogs;
use App\Models\LostDogs;
use App\Services\FileUploader\CoverImageUploader;
use App\Services\FileUploader\ListingsImagesUploader;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\Response as Response;

class LostDogService
{
    use AuthorizesRequests;

    /**
     * Create lost dog listing
     *
     * @return void
     */
    public function createLostDogListing(Request $request)
    {
        $user = $request->user();


        $image = (new CoverImageUploader($request->cover_photo, "listings"))
            ->resize()
            ->uploadImage();


        try {
            $dogListing    = Dogs::create($request->only('name', 'description', 'title', 'breed_id', 'dob', 'city_id', 'size') + [
                'user_id'       => $user->id,
                'slug'          => Str::slug($request->title),
                'cover_image'   => $image,
                'city_id'       =>  $request->city_id,
                'status_id'     => DogListingStatuses::ACTIVE,
                'gender'        => $request->gender,
                'listing_type'  => ListingTypesEnum::LOST,
            ]);

            //Create Lost Dog 
            $lostDogListing = LostDogs::create([
                'dog_id'        => $dogListing->id,
                'lost_at'       => $request->lost_date,
                'location_id'   => $request->location_id,
                'reward'        => $request->reward
            ]);

            return $dogListing;
        } catch (Exception $e) {
            return response(['error' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        //Handle Images upload
        (new ListingsImagesUploader($request->images, $dogListing->title, $dogListing->id))->uploadImage();
    }
}
