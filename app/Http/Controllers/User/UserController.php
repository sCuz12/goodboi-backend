<?php

namespace App\Http\Controllers\User;

use App\Enums\DogListingStatusesEnum;
use App\Enums\UserType;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Resources\DogResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserSingleResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class UserController
{
    public function index()
    {
        return User::paginate();
    }

    public function store(UserCreateRequest $request)
    {

        $user = (new UserService())->createUser($request);

        return response($user, Response::HTTP_CREATED);
    }

    public function update(Request $request)
    {

        $user = (new UserService())->updateUserInfo($request);
        return response(new UserSingleResource($user), Response::HTTP_ACCEPTED);
    }


    public function destroy($id)
    {
        User::destroy($id);
        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * show
     *
     * @param  mixed $id
     * @return void
     */
    public function show($id)
    {
        $user = User::find($id);

        return new UserResource($user);
    }

    public function getLoggedinUser()
    {
        return response(new UserResource(auth()->user()), Response::HTTP_OK);
    }

    /**
     * Validates that the authorized user is type of shelter
     *
     * @return JSON
     */
    public function getCurrentShelter()
    {
        if (!Auth::user()->isShelter()) {
            return response('Not authorized', Response::HTTP_FORBIDDEN);
        }
        return response('ok', Response::HTTP_OK);
    }

    /**
     * Gets all the data of logged in user (depends of type)
     *
     * @return JSON
     */
    public function getLoggedInData()
    {
        $user = Auth::user();
        if ($user->isShelter()) {
            return [
                'user'  => $user,
                'shelter' => $user->shelter,
            ];
        }

        return [
            'user'  => $user,
        ];
    }
    /**
     * Gets all the favourite listings of user
     *
     * @return JSON
     */
    public function getFavouritesListing()
    {
        $dogsListings = Auth::user()->favourites->where('status_id', DogListingStatusesEnum::ACTIVE);
        return DogResource::collection($dogsListings);
    }
}
