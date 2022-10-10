<?php

namespace App\Http\Controllers\Shelters;

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
        $deleted = (new ActionDogService())->deleteListing($id);

        return $deleted;
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
        return (new ActionDogService())->markAsAdopted($id);
    }
}
