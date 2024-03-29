<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Http\Interface\SocialAuthInterface;
use App\Http\Interfaces\SocialAuthInterface as InterfacesSocialAuthInterface;
use App\Http\Resources\UserSingleResource;
use App\Models\User;
use App\Models\UserProfile;
use App\Notifications\WelcomeEmail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthFacebookController extends Controller implements InterfacesSocialAuthInterface
{
    const PROVIDER_NAME = "facebook";
    /**
     * Create a redirect method to facebook api.
     *
     * @return void
     */
    public function redirectToProvider()
    {
        // retrieve social user info
        return  Socialite::driver(self::PROVIDER_NAME)->stateless()->redirect();
    }

    /**
     * Return a callback method from facebook api.
     *
     * @return callback URL from facebook
     */
    public function handleProviderCallback(Request $request)
    {

        try {
            $user     = Socialite::driver(SELF::PROVIDER_NAME)->stateless()->user();
        } catch (\Exception $exception) {

            return response()->json(['error' => 'Invalid credentials provided.'], 422);
        }

        try {
            $fullname    = $this->split_fullname($user->getName());
            $userFetched = User::where('email', '=',  $user->getEmail())->first();

            if (null == $userFetched) {
                $userCreated = User::create([
                    'email_verified_at' => now(),
                    'first_name'        => $fullname[0],
                    'last_name'         => $fullname[1],
                    'cover_photo'       => $user->getAvatar() ?? "",
                    'user_type'         => UserType::USER,
                    'email'             => $user->getEmail(),
                ]);
                //create row in user_profile
                UserProfile::firstOrCreate(['user_id' => $userCreated->id]);
                //send Register Email 
                $userCreated->notify(new WelcomeEmail());

                $isUserCreated = true;
            } else {
                $isUserCreated = false;
                $userCreated = $userFetched;
            }

            $userCreated->providers()->updateOrCreate(
                [
                    'provider' => SELF::PROVIDER_NAME,
                    'provider_id' => $user->getId(),
                ],
            );

            $token = $userCreated->createToken(UserType::USER, [UserType::USER])->accessToken;

            return [
                'user'  => new UserSingleResource($userCreated, $isUserCreated),
                'token' => $token
            ];
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    private function split_fullname($fullname)
    {
        $parts = explode(' ', $fullname);
        return $parts;
    }
}
