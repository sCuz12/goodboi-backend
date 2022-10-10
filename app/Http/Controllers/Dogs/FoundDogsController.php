<?php

namespace App\Http\Controllers\Dogs;

use App\Http\Controllers\Controller;
use App\Http\Resources\FoundDogsResource;
use App\Services\Dogs\FoundDogsService;
use App\Services\User\FoundDogService;
use Illuminate\Http\Request;

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
}
