<?php

namespace App\Http\Controllers\Dogs;

use App\Http\Resources\DogResource;
use App\Http\Resources\DogSingleResource;
use Illuminate\Http\Request;
use App\Services\DogService;
use App\Http\Controllers\Controller;
use App\Models\Shelter;
use App\Repositories\Interfaces\DogListingRepositoryInterface;
use Illuminate\Http\Response;

class DogsController extends Controller
{
    private DogService $dogService;

    public function __construct()
    {
        $this->dogService = new DogService();
    }
    /**
     * Check the request if has (size or shelter filters) otherwise fetch all active
     *
     * @return json
     */
    public function index(Request $request)
    {

        $dogsResults = $this->dogService->filterDogsByRequest($request);

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
        $dog = $this->dogService->getSingleDog($id);
        //Handle not found
        if (!$dog) {
            return response("Listing not found", 404);
        }
        //updates count of the view 
        $this->dogService->updateCountView($dog, request()->header('Client_Ip'));

        return new DogSingleResource($dog);
    }

    /**
     * Get Listings of specific shelter
     *
     * @param   $id
     * @param Request $request
     * @return JSON
     */
    public function shelterListings($id, Request $request)
    {
        $shelter = Shelter::find($id);
        if (!$shelter) {
            return response('Shelter not found', Response::HTTP_NOT_FOUND);
        }
        $listings = $this->dogService->getAllListingsOfShelter($shelter->id, $request);
        return DogResource::collection($listings);
    }
}
