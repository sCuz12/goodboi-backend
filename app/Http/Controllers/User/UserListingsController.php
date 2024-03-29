<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\DogResource;
use App\Http\Resources\FoundDogs\EditFoundDogResource;
use App\Http\Resources\LostDogs\LostDogEditResource;
use App\Repositories\DogRepository;
use App\Services\DogService;
use App\Traits\ApiResponser;
use Auth;
use Illuminate\Http\Response;

class UserListingsController extends Controller
{
    use ApiResponser;
    /**
     * Retrieves listings (found,lost) of a loggedin user
     *
     * @return void
     */
    public function userListings()
    {
        $user             = Auth::user();
        $lostDogsListings = (new DogService())->getAllListingsOfUser($user);

        return DogResource::collection($lostDogsListings);
    }

    public function showEdit($id)
    {

        $lostDogListing = DogRepository::findActiveListingById($id);

        $ableToUpdate = Auth::user()->can('showEditLostDog', $lostDogListing);
        if (!$ableToUpdate) {
            return $this->errorResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }
        if ($lostDogListing->isLostListingType()) {
            return new LostDogEditResource($lostDogListing);
        }
        if ($lostDogListing->isFoundListingType()) {
            return new EditFoundDogResource($lostDogListing);
        }
    }
}
