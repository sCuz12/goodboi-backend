<?php

namespace App\Http\Controllers;

use App\Http\Resources\DogResource;
use App\Http\Resources\DogSingleResource;
use Illuminate\Http\Request;
use App\Models\Dogs;
use App\Models\Shelter;
use App\Services\DogService;
use Illuminate\Http\Response;

class DogsController extends Controller
{
    /**
     * Check the request if has (size or shelter filters) otherwise fetch all active
     *
     * @return json
     */
    public function index(Request $request)
    {
        $dogService  = new DogService();
        $dogsResults = $dogService->filterDogsByRequest($request);

        return DogResource::collection($dogsResults);
    }

    /**
     * show specific dog listing information by slug
     *
     * @param  mixed $id
     * @return json
     */
    public function showById($id)
    {
        $dog = (new DogService())->getSingleDog($id);
        //Handle not found
        if (!$dog) {
            return response("Listing not found", 404);
        }
        //updates count of the view 
        (new DogService())->updateCountView($dog, request()->header('Client_Ip'));

        return new DogSingleResource($dog);
    }

    /**
     * Get Listings of specific shelter
     *
     * @param  int $id
     * @return JSON
     */
    public function shelterListings($id, Request $request)
    {
        $shelter = Shelter::find($id);
        if (!$shelter) {
            return response('Shelter not found', Response::HTTP_NOT_FOUND);
        }
        $listings = (new DogService())->getAllListingsOfShelter($shelter->id, $request);
        return DogResource::collection($listings);
    }
}
