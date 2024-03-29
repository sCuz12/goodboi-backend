<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Exceptions\ErrorUserRegistrationException;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Resources\UserSingleResource;
use App\Services\AuthService;
use App\Traits\ApiResponser;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use ApiResponser;
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            if ($user->isShelter()) {
                $scope = UserType::SHELTER;
                $token = $user->createToken($scope, [$scope])->accessToken;
            } else {
                $scope = UserType::USER;
                $token = $user->createToken($scope, [$scope])->accessToken;
            }

            return [
                'user'  => new UserSingleResource($user),
                'token' => $token
            ];
        }

        return response([
            'error' => 'Invalid Credentials!'
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = (new AuthService())->registerUser($request);
        } catch (ErrorUserRegistrationException $e) {
            return $e->render();
        } catch (Exception $e) {
            return $this->errorResposnse("Error");
        }


        return response($user, Response::HTTP_CREATED);
    }


    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = $this->getUser();
        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);
        return response($user, Response::HTTP_ACCEPTED);
    }

    /**
     * Logouts the user(deletes its token from db)
     *
     * @return void
     */
    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->AauthAcessToken()->delete();
        }
    }
}
