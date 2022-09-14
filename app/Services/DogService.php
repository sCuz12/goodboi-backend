<?php

namespace App\Services;

use App\Http\Requests\CreateDogListRequest;
use App\Http\Requests\EditDogListRequest;
use App\Http\Resources\DogResource;
use App\Models\AnimalHealthBook;
use App\Models\Shelter as Shelter;
use App\Models\Dogs;
use App\Models\DogsViewsLog;
use App\Services\FileUploader\CoverImageUploader;
use App\Services\FileUploader\ListingsImagesUploader;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DogService
{
    use AuthorizesRequests;

    /**
     * Create dog listing to the dogs table
     *
     * @param  CreateDogListRequest $request
     * @return Dog
     */
    public function createDogListing(CreateDogListRequest $request)
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


        $image      = (new CoverImageUploader($request->cover_photo, "listings"))->uploadImage();

        $dogList    = Dogs::create($request->only('name', 'description', 'title', 'breed_id', 'dob', 'city_id', 'size') + [
            'user_id' => $user_id,
            'shelter_id' => $shelter->id,
            'slug'     => Str::slug($request->title),
            'cover_image'    => $image,
            'city_id'    => $shelter->city->id,
            'status_id' => '1',
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
     * Get listings based on the request (if no filters return all active dogs otherwise filter)
     *
     * @param  Request $request
     * @return LengthAwarePaginator
     */
    public function filterDogsByRequest(Request $request)
    {

        if ($request->size == null && $request->shelter_id == null && $request->city === null) {
            //means no params added
            $dogs = Dogs::getAllActiveDogs();
            return $dogs;
        }

        $params = [];
        if ($request->size != null) {
            $params['size'] = $request->size;
        }
        if ($request->shelter_id != null) {
            $params['shelter_id'] = $request->shelter_id;
        }

        if ($request->city != null) {
            $params['city'] = $request->city;
        }

        $dogs = Dogs::getListingsByParams($params);

        return $dogs;
    }

    /**
     * Get All listings by Shelter id 
     *
     * @param  integer $shelter_id
     * @return LengthAwarePaginator
     */
    public function getAllListingsOfShelter($shelter_id)
    {
        $results = Dogs::getListingsByParams(['shelter_id' => $shelter_id]);
        return $results;
    }

    /**
     * Checks the Permission and deletes the record
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

        $deleted = $listing->delete();

        if (!$deleted) {
            return Response('Failed to delete listing', Response::HTTP_NOT_FOUND);
        }
        return Response('Listing deleted succesfully', Response::HTTP_ACCEPTED);
    }


    /**
     * checks if user has not already seen the listing incrase the counter 
     *
     * @param  Dogs $dog
     * @return void
     */
    public function updateCountView(Dogs $dog): void
    {
        //@todo get the ip from the next.js
        $ip = \Request::getClientIp();

        $userAlreadySeen = DogsViewsLog::isUserAlreadySeen($dog->id, $ip);

        if (!$userAlreadySeen) {
            Dogs::incrementViews($dog);
        }

        DogsViewsLog::insertViewLog($dog);
    }
}
