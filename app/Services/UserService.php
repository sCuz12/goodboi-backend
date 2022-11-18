<?php

namespace App\Services;

use App\Enums\ListingTypesEnum;
use App\Enums\UserType;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserCreateRequest;
use App\Models\Dogs;
use App\Models\Favourites;
use App\Models\User;
use App\Repositories\DogRepository;
use App\Services\FileUploader\CoverImageUploader;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserService
{

    protected DogRepository $dogRepository;

    public function __construct()
    {
        $this->dogRepository = (new DogRepository());
    }

    public function createUser(UserCreateRequest $request)
    {
        $user = User::create($request->only('first_name', 'last_name', 'email') + [
            'password' => Hash::make(1234),
        ]);
    }

    /**
     * Registers the user for first time
     *
     * @param  RegisterRequest $request
     * @return User
     */
    public function registerUser(RegisterRequest $request): User
    {
        //check from the request the user_type of user
        $user_type = $request->input('is_shelter') ? UserType::SHELTER : UserType::USER;

        $user = User::create($request->only('first_name', 'last_name', 'email', 'user_type') + [
            'password' => Hash::make($request->input('password')),
            'user_type' => $user_type
        ]);

        return $user;
    }

    /**
     * updateUserInfo
     *
     * @param  Request $request
     * @return User
     */
    public function updateUserInfo(Request $request)
    {

        $user       = \Auth::user(); // get auth user   
        $input      = $request->only('first_name', 'last_name', 'email', 'phone');

        if ($request->cover_photo) {
            $image      = (new CoverImageUploader($request->cover_photo, "users"))
                ->resize()
                ->uploadImage();
            $input = $input + ['cover_photo' => $image];
        }

        if ($request->phone) {
            $user->userProfile->update(['phone' => $request->phone]);
        }
        $user->update($input);

        return $user;
    }

    /**
     * Returns the stats of the user 
     * (favourites ,Active lost listings count ,Active found listngs count)
     *
     * @param  User $user
     * @return array
     */
    public function getUserStats(User $user): array
    {
        $data = [];

        $userFavouritedCount = Favourites::favouritesCountByUser($user);
        $activeLostDogCount  = $this->dogRepository->totalDogsListedByUser($user, ListingTypesEnum::LOST);
        $activeFoundDogCount = $this->dogRepository->totalDogsListedByUser($user, ListingTypesEnum::FOUND);

        $data[] = [
            'name' => "My Favourites",
            'count' => $userFavouritedCount,
            'url' => "/user/favourites"
        ];

        $data[] = [
            'name' => "Active Lost Dogs",
            'count' => $activeLostDogCount,
            'url' => "/user/mylistings"
        ];
        $data[] = [
            'name' => "Active Found Dogs",
            'count' => $activeFoundDogCount,
            'url' => "/user/mylistings"
        ];



        return $data;
    }
}
