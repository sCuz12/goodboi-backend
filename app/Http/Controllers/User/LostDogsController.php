<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\DogResource;
use App\Http\Resources\LostDogResource;
use App\Models\LostDogs;
use Illuminate\Http\Request;

class LostDogsController extends Controller
{
    /**
     * Retrieve all LOST dogs listings
     *
     * @return void
     */
    public function index()
    {
        $lostDogs = LostDogs::allActiveDogs();
        return LostDogResource::collection($lostDogs);
    }
}
