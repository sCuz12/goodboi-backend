<?php

namespace App\Services;

use App\Enums\ListingTypesEnum;
use App\Models\Dogs;
use App\Models\DogsViewsLog;
use App\Models\User;
use App\Repositories\Interfaces\DogListingRepositoryInterface;
use App\Traits\ApiResponser;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class DogService
{
    use AuthorizesRequests, ApiResponser;

    private  $adoptionDogRepository;

    public function __construct(DogListingRepositoryInterface $dogRepository)
    {
        $this->adoptionDogRepository = $dogRepository;
    }
    /**
     * Get listings based on the request (if no filters return all active dogs otherwise filter)
     *
     * @param  Request $request
     * @return LengthAwarePaginator
     */
    public function filterDogsByRequest(Request $request, string $type = ListingTypesEnum::ADOPT)
    {

        if (
            $request->size == null
            && $request->shelter_id == null
            && $request->city === null
            && $request->sortField === null
            && $request->gender === null
            && $request->maxAge === null
        ) {

            //means no params added
            switch ($type) {
                case ListingTypesEnum::ADOPT:
                    return $this->adoptionDogRepository->getAllDogs(ListingTypesEnum::ADOPT);
                    break;
                case ListingTypesEnum::LOST:
                    return $this->adoptionDogRepository->getAllDogs(ListingTypesEnum::LOST);
                    break;
                default;
            }
        }

        $params = [];

        if ($request->size != null) {
            $params['size'] = $request->size;
        }
        if ($request->shelter_id != null) {
            $params['shelter_id'] = $request->shelter_id;
        }

        if ($request->city != null) {

            $request->city = explode(',', $request->city);
            $params['city'] = $request->city;
        }

        if ($request->sortField) {
            $params['sortField'] = $request->sortField;
            $params['sortValue'] = $request->sortValue;
        }

        if ($request->gender && $request->gender != "b") {
            $params['gender'] = $request->gender;
        }

        if ($request->maxAge) {
            $params['minAge'] = $request->minAge ?? 0;
            $params['maxAge'] = $request->maxAge ?? 10;
        }

        $dogs = $this->adoptionDogRepository->getDogsByParams($params, ListingTypesEnum::ADOPT);

        return $dogs;
    }

    /**
     * Get All listings by Shelter id 
     *
     * @param  integer $shelter_id
     * @return LengthAwarePaginator
     */
    public function getAllListingsOfShelter($shelter_id, Request $request)
    {
        if ($request->status) {
            return Dogs::getListingsByParams(['shelter_id' => $shelter_id, 'status' => $request->status, 'orderBy' => 1]);
        }

        $results = Dogs::getListingsByParams(['shelter_id' => $shelter_id, 'orderBy' => 1]);

        return $results;
    }

    /**
     * Retrieves a single dog information for view
     *
     * @param  string $dogId
     * @return Dogs
     */
    public function getSingleDog(string $dogId)
    {
        $dogListing = $this->adoptionDogRepository->getDogById($dogId);
        return $dogListing;
    }

    /**
     * checks if user has not already seen the listing incrase the counter 
     *
     * @param  Dogs $dog
     * @param  String $clientIp
     * @return void
     */
    public function updateCountView(Dogs $dog, String $clientIp): void
    {
        $userAlreadySeen = DogsViewsLog::isUserAlreadySeen($dog->id, $clientIp);

        if (!$userAlreadySeen) {
            Dogs::incrementViews($dog);
        }

        DogsViewsLog::insertViewLog($dog, $clientIp);
    }

    public function getAllListingsOfUser(User $user)
    {
        $activeLostDogs = $this->adoptionDogRepository->getLostOrActiveDogsByUser($user);
        return $activeLostDogs;
    }
}
