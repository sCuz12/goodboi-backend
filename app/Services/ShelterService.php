<?php

namespace App\Services;

use App\Enums\DogListingStatusesEnum;
use App\Models\Dogs;
use App\Models\Shelter as Shelter;
use App\Models\User;
use App\Repositories\ShelterRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ShelterService
{
    use AuthorizesRequests;

    private $shelterRepository;

    public function __construct()
    {
        $this->shelterRepository = (new ShelterRepository());
    }

    public function getAllShelters(Request $request)
    {

        if ($request->city == null) {
            return $this->shelterRepository->getVerifiedShelters();
        }

        if ($request->city != null) {
            $params['city'] = $request->city;
        }

        $shelters = $this->shelterRepository->getSheltersByParams($params);

        return $shelters;
    }

    /**
     * Returns all the stats of specific shelter 
     *
     * @param  User $user
     * @return Array
     */
    public function getShelterStats(User $user)
    {
        $data                   = [];

        $dogActiveListingsCount = Dogs::getListingsCountByShelter($user->shelter->id, true);
        $totalFavourites        = Dogs::totalFavouritesByShelter($user->shelter);
        $dogListingCount        = Dogs::getListingsCountByShelter($user->shelter->id);
        $totalViews             = Dogs::totalViewsByShelter($user->shelter);
        $adoptedListings        = Dogs::adoptedListingsCountByUser($user->shelter);

        if (!$user->isShelter()) {
            return false;
        }

        $data[] = [
            'name' => "My Listings(Active)",
            'count' => $dogActiveListingsCount,
            'url' => "/shelter/mylistings/view"
        ];


        $data[] = [
            'name' => "My Listings(All)",
            'count' => $dogListingCount,
            'url' => "/shelter/mylistings/view"
        ];


        $data[] = [
            'name' => "My Listings (Adopted)",
            'count' => $adoptedListings,
        ];


        $data[] = [
            'name' => "Total Favourites",
            'count' => $totalFavourites,
        ];



        $data[] = [
            'name' => "Total Views",
            'count' => $totalViews,
        ];



        return $data;
    }
}
