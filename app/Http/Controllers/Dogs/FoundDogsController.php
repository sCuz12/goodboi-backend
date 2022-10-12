<?php

namespace App\Http\Controllers\Dogs;

use App\Exceptions\IdNotProvidedException;
use App\Http\Controllers\Controller;
use App\Http\Resources\FoundDogs\FoundDogsResource;
use App\Http\Resources\FoundDogs\SingleFoundDogResource;
use App\Services\Dogs\FoundDogsService;

class FoundDogsController extends Controller
{
    protected $foundDogsService;

    public function __construct()
    {
        $this->foundDogsService = (new FoundDogsService());
    }

    public function index()
    {
        $foundDogs = $this->foundDogsService->getAllActiveFoundDogs();
        return  FoundDogsResource::collection($foundDogs);
    }

    public function getSingle($id)
    {
        try {
            $foundDog = $this->foundDogsService->getActiveDogById($id);
        } catch (IdNotProvidedException $e) {
            return $e->render();
        }


        return new SingleFoundDogResource($foundDog);
    }
}
