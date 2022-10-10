<?php

namespace  App\Services\User;

use App\Enums\DogListingStatusesEnum;
use App\Enums\ListingTypesEnum;
use App\Exceptions\CreateFoundDogListingException;
use App\Models\Dogs;
use App\Models\FoundDogs;
use App\Services\FileUploader\CoverImageUploader;
use App\Services\FileUploader\ListingsImagesUploader;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Str;

class FoundDogService
{
    use AuthorizesRequests;

    /**
     * Creates found dog listing
     *
     * @return void
     */
    public function createFoundDogListing(Request $request)
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
                'status_id'     => DogListingStatusesEnum::ACTIVE,
                'gender'        => $request->gender,
                'listing_type'  => ListingTypesEnum::FOUND,
            ]);

            //Create Lost Dog 
            $foundDogListing = FoundDogs::create([
                'dog_id'        => $dogListing->id,
                'found_date'       => $request->lost_date,
                'location_id'   => $request->location_id,
                'reward'        => $request->reward
            ]);

            //Handle Images upload
            (new ListingsImagesUploader($request->images, $dogListing->title, $dogListing->id))->uploadImage();

            return  $dogListing;
        } catch (Exception $e) {
            //TODO :LOG The error
            throw new CreateFoundDogListingException($e);
        }
    }
}
