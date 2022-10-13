<?php

namespace App\Http\Controllers\User;

use App\Exceptions\ListingNotFoundException;
use App\Exceptions\NotListingOwnerException;
use App\Exceptions\UnableToUploadListingException;
use App\Services\DogService;
use App\Services\LostDogService;
use App\Services\Shelters\ActionDogService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserLostDogController
{
    use ApiResponser;

    public function createLostDogListing(Request $request)
    {
        try {
            (new LostDogService())->createLostDogListing($request);
            return response("Listing created succesfully", Response::HTTP_ACCEPTED);
        } catch (UnableToUploadListingException $e) {
            return $e->render();
        } catch (\Exception $e) {
            return response("Unable to create listing", Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function destroy($dogId)
    {

        try {
            (new ActionDogService())->deleteListing($dogId);
            return $this->successResponse("Listing Deleted succesfully", Response::HTTP_ACCEPTED);
        } catch (NotListingOwnerException | ListingNotFoundException $e) {
            return $e->render();
        }
    }

    public function update(Request $request, string $dogId)
    {
        try {
            (new LostDogService())->updateListing($request, $dogId);
            return $this->successResponse("Listing updated succesfully", Response::HTTP_ACCEPTED);
        } catch (ListingNotFoundException | NotListingOwnerException $e) {
            return $e->render();
        }
    }
}
