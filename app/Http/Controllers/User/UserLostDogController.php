<?php

namespace App\Http\Controllers\User;

use App\Services\LostDogService;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;

class UserLostDogController
{
    public function createLostDogListing(Request $request)
    {
        $dogListing = (new LostDogService())->createLostDogListing($request);
        return response($dogListing, HttpResponse::HTTP_ACCEPTED);
    }
}
