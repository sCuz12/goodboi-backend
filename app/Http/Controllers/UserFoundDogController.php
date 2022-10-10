<?php

namespace App\Http\Controllers;

use App\Exceptions\CreateFoundDogListingException;
use App\Services\User\FoundDogService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserFoundDogController extends Controller
{
    use ApiResponser;

    public function create(Request $request)
    {
        try {
            $dogListing = (new FoundDogService())->createFoundDogListing($request);
            return $this->successResponse($dogListing, Response::HTTP_OK);
        } catch (CreateFoundDogListingException $e) {
            return $e->render();
        }
    }
}
