<?php

namespace App\Http\Controllers\Shelters;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateShelterProfileRequest;
use App\Http\Resources\DogResource;
use App\Http\Resources\ShelterResource;
use App\Http\Resources\UserSingleResource;
use App\Services\DogService;
use App\Services\ShelterService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ShelterController extends Controller
{

    public function index(Request $request)
    {

        $sheltersResults =  (new ShelterService())->getAllShelters($request);
        return ShelterResource::collection($sheltersResults);
    }
    /**
     * Storing Shelter info to database
     *
     * @return void
     */
    public function store(CreateShelterProfileRequest $request)
    {

        $user = Auth::user();

        $user->shelter->update($request->only('shelter_name', 'address', 'phone', 'description', 'user_id', 'city_id') + [
            'slug' => Str::slug($request->shelter_name),
            'is_profile_complete' => 1,
            'facebook_pagename'   => $request->facebook_pagename,
            'instagram'           => $request->instagram ?? "",
            'facebook'            => $request->facebook ?? "",

        ]);

        return response(new UserSingleResource($user), Response::HTTP_CREATED);
    }

    /**
     * Returns all the listings animals of loggedin shelter
     *
     * @return JSON
     */
    public function shelterListings(Request $request)
    {
        $user = Auth::user();

        $listings = (new DogService())->getAllListingsOfShelter($user->shelter->id, $request);
        return DogResource::collection($listings);
    }

    /**
     * Returns all the status of the Auth Shelter
     *
     * @return JSON
     */
    public function getStats()
    {
        $user = Auth::user();
        $data = (new ShelterService())->getShelterStats($user);
        return response(['data' => $data], Response::HTTP_ACCEPTED);
    }
}
