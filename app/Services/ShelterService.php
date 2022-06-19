<?php

namespace App\Services;

use App\Models\Shelter as Shelter;
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
}
