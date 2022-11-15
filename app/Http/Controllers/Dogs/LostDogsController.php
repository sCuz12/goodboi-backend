<?php

namespace App\Http\Controllers\Dogs;

use App\Enums\ListingTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\LostDogResource;
use App\Http\Resources\LostDogs\LostDogSingleResource;
use App\Repositories\Interfaces\DogListingRepositoryInterface;
use App\Services\DogService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class LostDogsController extends Controller
{
    use ApiResponser;


    private DogService $dogService;

    public function __construct(DogListingRepositoryInterface $dogRepository)
    {
        $this->dogService = new DogService($dogRepository);
    }

    /**
     * Retrieve all LOST dogs listings
     *
     * @return void
     */
    public function index(Request $request)
    {
        $lostDogs = $this->dogService->filterDogsByRequest($request, ListingTypesEnum::LOST);
        return LostDogResource::collection($lostDogs);
    }

    public function getSingle(string $dogId)
    {
        $lostDog = ($this->dogService)->getSingleDog($dogId);

        if (!$lostDog) {
            return $this->errorResponse("Listing not found", 404);
        }

        return new LostDogSingleResource($lostDog);
    }
}
