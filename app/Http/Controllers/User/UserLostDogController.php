<?php

namespace App\Http\Controllers\User;

use App\Services\LostDogService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class UserLostDogController
{
    use ApiResponser;

    public function createLostDogListing(Request $request)
    {
        return (new LostDogService())->createLostDogListing($request);
    }

    public function destroy($dogId)
    {

        $result = (new LostDogService())->deleteListing($dogId);
        return $result;
    }

    public function update(Request $request, string $dogId)
    {
        return (new LostDogService())->updateListing($request, $dogId);
    }
}
