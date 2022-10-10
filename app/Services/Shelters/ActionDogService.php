<?php

namespace App\Services\Shelters;

use App\Enums\ListingTypesEnum;
use App\Models\AnimalHealthBook;
use Illuminate\Http\Request;
use App\Models\Dogs;
use App\Services\FileUploader\CoverImageUploader;
use App\Services\FileUploader\ListingsImagesUploader;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Str;
use App\Models\Shelter as Shelter;
use File;
use App\Http\Requests\CreateDogListRequest;
use App\Traits\ApiResponser;
use App\Enums\CoverImagesPathEnum;
use Illuminate\Support\Facades\Auth;
use App\Enums\DogListingStatusesEnum;

class ActionDogService
{
    use AuthorizesRequests, ApiResponser;

    /**
     * Create dog listing  (ADOPTION) to the dogs table
     *
     * @param  CreateDogListRequest $request
     * @return Dog
     */
    public function createAdoptionDogListing(CreateDogListRequest $request)
    {
        $user = $request->user();
        try {
            $this->authorize('create', Dogs::class);
        } catch (Exception $e) {
            return response("Not a shelter", Response::HTTP_UNAUTHORIZED);
        }

        $user_id    = $user->id;

        $shelter    = Shelter::where('user_id', $user_id)->first();

        if ($shelter == null) {
            return response("Need to update shelter profile information", Response::HTTP_UNAUTHORIZED);
        }


        $image = (new CoverImageUploader($request->cover_photo, "listings"))
            ->resize()
            ->uploadImage();

        $dogList    = Dogs::create($request->only('name', 'description', 'title', 'breed_id', 'dob', 'city_id', 'size') + [
            'user_id' => $user_id,
            'shelter_id' => $shelter->id,
            'slug'     => Str::slug($request->title),
            'cover_image'    => $image,
            'city_id'    => $shelter->city->id,
            'status_id' => '1',
            'gender'    => $request->gender,
            'listing_type' => ListingTypesEnum::ADOPT,
        ]);

        //Create health book record for the dog 
        $animalBook = AnimalHealthBook::create([
            'dog_id' => $dogList->id,
        ]);
        //TODO : Investigate if works well

        //Add the vaccinations into pivot table
        if ($request->vaccinations) {
            $animalBook->vaccinations()->attach($request->vaccinations);
        }


        //Handle Images upload
        (new ListingsImagesUploader($request->images, $dogList->title, $dogList->id))->uploadImage();

        return $dogList;
    }

    /**
     * Updates a specific Dog listing to db
     *
     * @param  Request $request
     * @param  int $dogList_id
     * @return Dogs
     */
    public function editDogListing(Request $request, int $dogList_id)
    {
        $dogList = Dogs::findOrFail($dogList_id);
        //Checks if its dog post owner
        try {
            $this->authorize('update', $dogList);
        } catch (Exception $e) {
            return response("Not owner of this listing", Response::HTTP_UNAUTHORIZED);
        }

        if ($request->cover_photo) {
            $image      = (new CoverImageUploader($request->cover_photo, "listings"))->uploadImage();
            $request->request->add(['cover_image' => $image]);
        }

        $dogList->update($request->all());

        //Handle Vaccinations update 
        if ($request->vaccinations) {
            $animalBook = AnimalHealthBook::where('dog_id', $dogList->id)->first();
            $animalBook->vaccinations()->sync($request->vaccinations, ['dog_id', $dogList->id]);
        }

        //Handle Images upload if listing image are changed
        if ($request->images) {

            (new ListingsImagesUploader($request->images, $dogList->title, $dogList->id))->uploadImage(true);
        }

        return $dogList;
    }

    /**
     * Checks the Permission and deletes the record completely and its files from server
     *
     * @param  int $id
     * @return bool| Response
     */
    public function deleteListing($id)
    {
        $listing = Dogs::find($id);

        $ableToUpdate = Auth::user()->can('update', $listing);

        //check the permissions
        if (!$ableToUpdate) {
            return response("You do not own this listing", Response::HTTP_FORBIDDEN);
        }

        $coverImageFileName = $listing->cover_image;
        //extract the url from collection and concat with public path
        $listingFilesNames  = $listing->dog_images->map(function ($item) {
            return public_path($item['url']);
        });

        try {
            $deleted = $listing->delete();
            //TODO : refactor the delete of file cause it is repeated on lost dogs
            //delete cover image
            if (File::exists(public_path(CoverImagesPathEnum::LISTINGS . "/" . $coverImageFileName))) {
                File::delete(public_path(CoverImagesPathEnum::LISTINGS . "/" . $coverImageFileName));
            }
            //delete listings file
            File::delete(...$listingFilesNames);
        } catch (Exception  $e) {
            //TODO : Log action
            return $this->errorResponse("Failed to delete", Response::HTTP_CONFLICT);
        }


        if (!$deleted) {
            return Response('Failed to delete listing', Response::HTTP_NOT_FOUND);
        }
        return Response('Listing deleted succesfully', Response::HTTP_ACCEPTED);
    }

    /**
     * Update the status of dog as adopted(2)
     *
     * @return void
     */
    public function markAsAdopted($id)
    {
        $dogListing = Dogs::find($id);
        if (!$dogListing) {
            return $this->errorResponse("Listing not found", Response::HTTP_NOT_FOUND);
        }

        try {
            $this->authorize('edit', $dogListing);
        } catch (Exception $e) {
            return $this->errorResponse("Not owner of this listing", Response::HTTP_UNAUTHORIZED);
        }

        $dogListing->status_id = DogListingStatusesEnum::ADOPTED;
        $dogListing->save();
        return $this->successResponse("ok", Response::HTTP_OK);
    }
}
