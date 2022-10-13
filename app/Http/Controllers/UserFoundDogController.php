<?php

namespace App\Http\Controllers;

use App\Exceptions\CreateFoundDogListingException;
use App\Exceptions\IncorrectListingTypeException;
use App\Exceptions\ListingNotFoundException;
use App\Exceptions\NotListingOwnerException;
use App\Http\Resources\FoundDogs\EditFoundDogResource;
use App\Services\Dogs\FoundDogsService;
use App\Services\DogService;
use App\Services\Shelters\ActionDogService;
use App\Services\User\FoundDogService;
use App\Traits\ApiResponser;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserFoundDogController extends Controller
{
    use ApiResponser;

    /**
     * @var FoundDogService
     */
    protected $foundDogService;

    public function __construct()
    {
        $this->foundDogService = (new FoundDogService());
    }
    public function create(Request $request)
    {
        try {
            $dogListing = $this->foundDogService->createFoundDogListing($request);
            return $this->successResponse($dogListing, Response::HTTP_ACCEPTED);
        } catch (CreateFoundDogListingException $e) {
            return $e->render();
        }
    }

    public function showEdit($id)
    {

        $dogListing = (new FoundDogsService())->getActiveDogById($id);

        $ableToUpdate = Auth::user()->can('showEditLostDog', $dogListing);

        if (!$ableToUpdate) {
            return $this->errorResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }


        return new EditFoundDogResource($dogListing);
    }

    public function update($id, Request $request)
    {
        try {
            $dogListing = $this->foundDogService->editFoundDogListing($id, $request);
            if (!$dogListing) {
                return $this->errorResponse("Found dog not updated", Response::HTTP_CONFLICT);
            }

            return $this->successResponse($dogListing, Response::HTTP_ACCEPTED);
        } catch (NotListingOwnerException | ListingNotFoundException $e) {
            return $e->render();
        }
    }

    public function destroy($id)
    {
        try {
            (new ActionDogService())->deleteListing($id);
            return $this->successResponse("Listing Deleted succesfully", Response::HTTP_ACCEPTED);
        } catch (
            ListingNotFoundException
            |
            NotListingOwnerException $e
        ) {

            return $e->render();
        }
    }
}
