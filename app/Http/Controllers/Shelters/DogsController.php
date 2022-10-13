<?php

namespace App\Http\Controllers\Shelters;

use App\Exceptions\ListingNotFoundException;
use App\Exceptions\MissingShelterInfoException;
use App\Exceptions\NotListingOwnerException;
use App\Exceptions\UnableToDeleteListingException;
use App\Exceptions\UnableToEditListingException;
use App\Exceptions\UnableToUploadListingException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDogListRequest;
use App\Http\Resources\DogEditSingleResource;
use App\Models\Dogs;
use App\Services\Shelters\ActionDogService;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response as Response;

class DogsController extends Controller
{
    use ApiResponser;

    /**
     * TODO : Only for Shelters can allow this controller
     *
     * @param  CreateDogListRequest $request
     * @return void
     */
    public function store(CreateDogListRequest $request)
    {
        try {
            $dogList = (new ActionDogService())->createAdoptionDogListing($request);
        } catch (
            MissingShelterInfoException
            | UnableToUploadListingException
            | NotListingOwnerException $e
        ) {
            $e->render();
        }

        return response($dogList, Response::HTTP_ACCEPTED);
    }

    public function update(HttpRequest $request, $id)
    {
        try {
            $dogList = (new ActionDogService())->editDogListing($request, $id);
        } catch (
            NotListingOwnerException
            | ListingNotFoundException | UnableToEditListingException $e
        ) {
            return $e->render();
        }

        return response($dogList, Response::HTTP_ACCEPTED);
    }



    public function destroy($id)
    {
        try {
            $deleted = (new ActionDogService())->deleteListing($id);
        } catch (
            NotListingOwnerException   |
            UnableToDeleteListingException $e
        ) {
            return $e->render();
        }

        return $this->successResponse("Listing Delete succesfully", Response::HTTP_ACCEPTED);
    }

    /**
     * Returns the dog listing information of specific listing
     *
     * @return void
     */
    public function showEdit($id)
    {

        try {
            $dogListing = (new  ActionDogService())->showEdit($id);
        } catch (ListingNotFoundException | NotListingOwnerException $e) {

            return $e->render();
        } catch (Exception $e) {
            //TODO: Handle general 
        }

        return new DogEditSingleResource($dogListing);
    }

    /**
     * Update the status of dog as adopted
     *
     * @return void
     */
    public function markAsAdopted($id)
    {
        try {
            (new ActionDogService())->markAsAdopted($id);
            return $this->successResponse("ok", Response::HTTP_OK);
        } catch (ListingNotFoundException | NotListingOwnerException $e) {
            return $e->render();
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_CONFLICT);
        }
    }
}
