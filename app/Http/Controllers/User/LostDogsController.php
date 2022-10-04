<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\LostDogResource;
use App\Services\LostDogService;
use Illuminate\Http\Request;

class LostDogsController extends Controller
{
    /**
     * Retrieve all LOST dogs listings
     *
     * @return void
     */
    public function index(Request $request)
    {
        $lostDogs = (new LostDogService())->filterLostDogsByRequest($request);
        return LostDogResource::collection($lostDogs);
    }
}
