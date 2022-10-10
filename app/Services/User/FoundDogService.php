<?php

namespace  App\Services\User;

use App\Enums\CoverImagesPathEnum;
use App\Enums\DogListingStatusesEnum;
use App\Enums\ListingTypesEnum;
use App\Exceptions\CreateFoundDogListingException;
use App\Exceptions\IncorrectListingTypeException;
use App\Exceptions\ListingNotFoundException;
use App\Exceptions\NotListingOwnerException;
use App\Models\Dogs;
use App\Models\FoundDogs;
use App\Services\FileUploader\CoverImageUploader;
use App\Services\FileUploader\ListingsImagesUploader;
use Auth;
use Exception;
use File;
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

    public function editFoundDogListing(string $dogId, Request $request): Dogs|false
    {

        $dogListing = Dogs::findOrFail($dogId);

        if (!$dogListing) {
            throw new ListingNotFoundException;
        }

        $ableToUpdate = Auth::user()->can('editFoundDog', $dogListing);

        if (!$ableToUpdate) {
            throw new NotListingOwnerException;
        }

        if ($request->location_id) {
            $dogListing->foundDog->update([
                'location_id' => $request->location_id
            ]);
        }

        $updated = $dogListing->update($request->all());

        if (!$updated) {
            return false;
        }
        return $dogListing;
    }

    public function destroyFoundDogListing(string $dogId): bool
    {
        $dogListing = Dogs::find($dogId);

        if (!$dogListing) {
            throw new ListingNotFoundException;
        }

        if (!$dogListing->isFoundListingType()) {
            throw new IncorrectListingTypeException;
        }

        $ableToDelete = Auth::user()->can('editFoundDog', $dogListing);

        if (!$ableToDelete) {
            throw new NotListingOwnerException;
        }


        $coverImageFileName = $dogListing->cover_image;
        //extract the url from collection and concat with public path
        $listingFilesNames = $dogListing->dog_images->map(function ($item) {
            return public_path($item['url']);
        });

        try {
            $dogListing->foundDog->delete();
            $dogListing->delete();
            //delete cover image
            if (File::exists(public_path(CoverImagesPathEnum::LISTINGS . "/" . $coverImageFileName))) {
                File::delete(public_path(CoverImagesPathEnum::LISTINGS . "/" . $coverImageFileName));
            }
            //delete listings file
            File::delete(...$listingFilesNames);
            return true;
        } catch (Exception $e) {
            //TODO : Log here
            return false;
        }
    }
}
