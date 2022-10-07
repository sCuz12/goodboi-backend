<?php

namespace App\Http\Controllers\User;

use App\Http\Resources\LostDogResource;
use App\Http\Resources\LostDogs\DogsLostResource;
use App\Http\Resources\LostDogs\LostDogEditResource;
use App\Models\LostDogs;
use App\Services\LostDogService;
use App\Traits\ApiResponser;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserLostDogController
{
    use ApiResponser;

    public function createLostDogListing(Request $request)
    {
        return (new LostDogService())->createLostDogListing($request);
    }

    public function userListings()
    {
        $user             = Auth::user();
        $lostDogsListings = (new LostDogService())->getAllListingsOfUser($user);

        return DogsLostResource::collection($lostDogsListings);
    }
    public function destroy($dogId)
    {

        $result = (new LostDogService())->deleteListing($dogId);
        return $result;
    }

    public function showEdit($id)
    {
        $lostDogListing = LostDogs::findLostDogById($id);

        $ableToUpdate = Auth::user()->can('showEditLostDog', $lostDogListing);
        if (!$ableToUpdate) {
            return $this->errorResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }
        return new LostDogEditResource($lostDogListing);
    }

    public function update(Request $request, string $dogId)
    {
        return (new LostDogService())->updateListing($request, $dogId);
    }
}
