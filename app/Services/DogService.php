<?php

namespace App\Services;

use App\Enums\CoverImagesPathEnum;
use App\Models\Dogs;
use App\Models\DogsViewsLog;
use App\Traits\ApiResponser;
use Exception;
use File;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DogService
{
    use AuthorizesRequests, ApiResponser;

    /**
     * Get listings based on the request (if no filters return all active dogs otherwise filter)
     *
     * @param  Request $request
     * @return LengthAwarePaginator
     */
    public function filterDogsByRequest(Request $request)
    {

        if (
            $request->size == null
            && $request->shelter_id == null
            && $request->city === null
            && $request->sort === null
            && $request->gender === null
        ) {
            //means no params added
            $dogs = Dogs::getAllActiveAdoptionDogs();
            return $dogs;
        }

        $params = [];
        if ($request->size != null) {
            $params['size'] = $request->size;
        }
        if ($request->shelter_id != null) {
            $params['shelter_id'] = $request->shelter_id;
        }

        if ($request->city != null) {
            $params['city'] = $request->city;
        }

        if ($request->sort) {
            $params['sortField'] = $request->sort;
            $params['sortValue'] = $request->sortValue;
        }

        if ($request->gender) {
            $params['gender'] = $request->gender;
        }

        $dogs = Dogs::getListingsByParams($params);

        return $dogs;
    }

    /**
     * Get All listings by Shelter id 
     *
     * @param  integer $shelter_id
     * @return LengthAwarePaginator
     */
    public function getAllListingsOfShelter($shelter_id)
    {
        $results = Dogs::getListingsByParams(['shelter_id' => $shelter_id]);
        return $results;
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
}
