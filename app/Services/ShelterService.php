<?php

namespace App\Services;

use App\Models\Dogs;
use App\Models\Shelter as Shelter;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ShelterService
{
    use AuthorizesRequests;

    public function getAllShelters(Request $request)
    {

        if ($request->city == null) {
            return Shelter::getVerifiedShelters();
        }

        if ($request->city != null) {
            $params['city'] = $request->city;
        }

        $shelters = Shelter::getSheltersByParams($params);
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
        $data = [];
        if (!$user->isShelter()) {
            return false;
        }
        $dogActiveListingsCount = Dogs::getListingsCountByShelter($user->shelter->id, true);
        $data[] = [
            'name' => "My Listings(Active)",
            'count' => $dogActiveListingsCount,
            'url' => "/shelter/mylistings/view"
        ];


        $dogListingCount = Dogs::getListingsCountByShelter($user->shelter->id);
        $data[] = [
            'name' => "My Listings(All)",
            'count' => $dogListingCount,
            'url' => "/shelter/mylistings/view"
        ];

        return $data;
    }
}
