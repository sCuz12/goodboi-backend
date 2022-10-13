<?php

namespace App\Http\Controllers\Shelters;

use App\Exceptions\ListingNotFoundException;
use App\Exceptions\NotListingOwnerException;
use App\Exceptions\UnableToDeleteListingException;
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
        $dogList = (new ActionDogService())->createAdoptionDogListing($request);
        return response($dogList, Response::HTTP_ACCEPTED);
    }

    public function update(HttpRequest $request, $id)
    {

        $dogList = (new ActionDogService())->editDogListing($request, $id);

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
        $dogListing = Dogs::findOrFail($id);
        try {
            $this->authorize('edit', $dogListing);
        } catch (Exception $e) {
            return response("Not owner of this listing", Response::HTTP_UNAUTHORIZED);
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
        } catch (ListingNotFoundException $e) {
            return $e->render();
        } catch (NotListingOwnerException $e) {
            return $e->render();
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_CONFLICT);
        }
    }
}
