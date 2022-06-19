<?php

namespace App\Services;

use App\Enums\UserType;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserCreateRequest;
use App\Models\Shelter;
use App\Models\User;
use App\Notifications\WelcomeEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthService
{

    public function __construct()
    {
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
        //Default picture based of the user type
        $defaultPicture = $user_type === UserType::SHELTER ? "shelter_default.png" : "user_default.png";

        $user = User::create($request->only('first_name', 'last_name', 'email', 'user_type') + [
            'password' => Hash::make($request->input('password')),
            'user_type' => $user_type,
            'cover_photo' => $defaultPicture,
        ]);

        //Create record in shelter table
        if ($user_type === UserType::SHELTER) {
            Shelter::create([
                'user_id' => $user->id,
            ]);
        }

        $user->notify(new WelcomeEmail());

        return $user;
    }
}
