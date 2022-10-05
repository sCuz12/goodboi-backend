<?php

namespace App\Http\Controllers\User;

use App\Http\Resources\LostDogResource;
use App\Services\LostDogService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;

class UserLostDogController
{

    public function createLostDogListing(Request $request)
    {
        return (new LostDogService())->createLostDogListing($request);
    }

    public function userListings()
    {
        $user             = Auth::user();
        $lostDogsListings = (new LostDogService())->getAllListingsOfUser($user);

        return LostDogResource::collection($lostDogsListings);
    }
    public function destroy($dogId)
    {

        $result = (new LostDogService())->deleteListing($dogId);
        return $result;
    }
}
