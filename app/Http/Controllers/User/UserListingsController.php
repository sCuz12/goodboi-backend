<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\DogResource;
use App\Services\DogService;
use Auth;
use Illuminate\Http\Request;

class UserListingsController extends Controller
{
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
}
