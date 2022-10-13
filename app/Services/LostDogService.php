<?php

namespace App\Services;

use App\Enums\CoverImagesPathEnum;
use App\Enums\DogListingStatusesEnum;
use App\Enums\ListingTypesEnum;
use App\Models\Dogs;
use App\Models\LostDogs;
use App\Models\User;
use App\Services\FileUploader\CoverImageUploader;
use App\Services\FileUploader\ListingsImagesUploader;
use App\Traits\ApiResponser;
use Auth;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\Response as Response;
use Illuminate\Support\Facades\File;

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

            return $this->showOne($dogListing, Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            //TODO :LOG The error
            return $this->errorResponse("Error occured while creating lost dog listing", Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        //Handle Images upload
        (new ListingsImagesUploader($request->images, $dogListing->title, $dogListing->id))->uploadImage();
    }

    public function filterLostDogsByRequest(Request $request)
    {

        if (!$request->sortBy) {
            $lostDogs = LostDogs::allActiveDogs();
            return $lostDogs;
        }

        $params = [];
        if ($request->sortBy != null) {
            $params['sortBy'] = $request->sortBy;
            $params['sortValue'] = $request->sortValue ?? "desc";
        }

        return LostDogs::allLostDogsByParams($params);
    }

    public function getSingleDog(string $dogId)
    {

        $dogListing = LostDogs::findLostDogById($dogId);
        return $dogListing;
    }

    /**
     * Handles the deletion of lost dog and return according json response
     *
     * @param  mixed $dogId
     * @return void
     */
    public function deleteListing($dogId)
    {
        $listing = Dogs::find($dogId);

        if (!$listing) {
            return $this->errorResponse("Listing not found", Response::HTTP_NOT_FOUND);
        }

        if (!$listing->isLostListingType()) {
            return $this->errorResponse("Not a lost listing type", Response::HTTP_NOT_ACCEPTABLE);
        }

        $ableToDelete = Auth::user()->can('deleteLostDog', $listing);

        if (!$ableToDelete) {
            return $this->errorResponse("Not authorized", Response::HTTP_UNAUTHORIZED);
        }

        $coverImageFileName = $listing->cover_image;
        //extract the url from collection and concat with public path
        $listingFilesNames = $listing->dog_images->map(function ($item) {
            return public_path($item['url']);
        });

        try {
            $listing->lostDog->delete();
            $listing->delete();
            //delete cover image
            if (File::exists(public_path(CoverImagesPathEnum::LISTINGS . "/" . $coverImageFileName))) {
                File::delete(public_path(CoverImagesPathEnum::LISTINGS . "/" . $coverImageFileName));
            }
            //delete listings file
            File::delete(...$listingFilesNames);
            return $this->successResponse("Listing Deleted Succesfully", Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            //TODO : Log here
            return $this->errorResponse("Failed to delete", Response::HTTP_CONFLICT);
        }
    }

    public function updateListing(Request $request, string $dogId)
    {
        $dogListing = Dogs::findOrFail($dogId);

        $ableToUpdate = Auth::user()->can('showEditLostDog', $dogListing);
        if (!$ableToUpdate) {
            return $this->errorResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
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
            return $this->successResponse($dogListing, Response::HTTP_ACCEPTED);
        } else {
            return $this->errorResponse("Not able to update", Response::HTTP_CONFLICT);
        }
    }
}
