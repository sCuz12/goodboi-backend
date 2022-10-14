<?php

namespace App\Services;

use App\Enums\CoverImagesPathEnum;
use App\Enums\DogListingStatusesEnum;
use App\Enums\ListingTypesEnum;
use App\Exceptions\ListingNotFoundException;
use App\Exceptions\NotListingOwnerException;
use App\Exceptions\UnableToUploadListingException;
use App\Models\Dogs;
use App\Models\LostDogs;
use App\Models\User;
use App\Services\FileUploader\CoverImageUploader;
use App\Services\FileUploader\ListingsImagesUploader;
use App\Traits\ApiResponser;
use Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\Response as Response;

class LostDogService
{
    use AuthorizesRequests, ApiResponser;

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
                'status_id'     => DogListingStatusesEnum::ACTIVE,
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

            //Handle Images upload
            (new ListingsImagesUploader($request->images, $dogListing->title, $dogListing->id))->uploadImage();

            return true;
        } catch (\Throwable $e) {
            //TODO :LOG The error
            throw new UnableToUploadListingException;
        }


        //Handle Images upload
        (new ListingsImagesUploader($request->images, $dogListing->title, $dogListing->id))->uploadImage();
    }

    public function updateListing(Request $request, string $dogId)
    {
        $dogListing = Dogs::findOrFail($dogId);

        if (!$dogListing instanceof Dogs) {
            throw new ListingNotFoundException;
        }

        $ableToUpdate = Auth::user()->can('showEditLostDog', $dogListing);

        if (!$ableToUpdate) {
            throw new NotListingOwnerException;
        }

        if ($request->location_id) {
            $dogListing->lostDog->update([
                'location_id' => $request->location_id
            ]);
        }

        if ($request->reward) {
            $dogListing->lostDog->update([
                'reward' => $request->reward
            ]);
        }

        $updated = $dogListing->update($request->all());

        if ($updated) {
            return true;
        } else {
            return false;
        }
    }
}
