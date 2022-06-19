<?php

namespace App\Http\Controllers\Shelters;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDogListRequest;
use App\Http\Requests\EditDogListRequest;
use App\Http\Resources\DogResource;
use App\Models\Dogs;
use App\Services\DogService;
use Exception;
use Symfony\Component\HttpFoundation\Response as Response;

class DogsController extends Controller
{

    /**
     * TODO : Only for Shelters can allow this controller
     *
     * @param  CreateDogListRequest $request
     * @return void
     */
    public function store(CreateDogListRequest $request)
    {
        $dogList = (new DogService())->createDogListing($request);
        return response($dogList, Response::HTTP_ACCEPTED);
    }

    public function update(EditDogListRequest $request, $id)
    {

        $dogList = (new DogService())->editDogListing($request, $id);

        return response($dogList, Response::HTTP_ACCEPTED);
    }



    public function destroy($id)
    {
        $deleted = (new DogService())->deleteListing($id);
        if ($deleted) {
            return Response('Listing deleted succesfully', Response::HTTP_ACCEPTED);
        }
        return Response('Failed to delete listing', Response::HTTP_NOT_FOUND);
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
        return new DogResource($dogListing);
    }
}
