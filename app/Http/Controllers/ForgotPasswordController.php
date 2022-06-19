<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\ResetPasswordActionRequest;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{

    /**
     * forgotPassword
     *
     * @param PasswordResetRequest $request
     * @return JSON
     */
    public function forgotPassword(PasswordResetRequest $request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response(["msg" => 'Reset password link sent on your email id.'], 200);
        }

        //means something went wrong
        return response(['msg' => "Error while senting the reset email"], 500);
    }

    /**
     * passwordReset
     *
     * @param  ResetPasswordActionRequest $request
     * @return JSON
     */
    public function passwordReset(ResetPasswordActionRequest $request)
    {
        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user) use ($request) {
            $user->forceFill([
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60)
            ])->save();
        });

        if ($status == Password::PASSWORD_RESET) {
            return response(["message" => "Password reset succesfully"], 200);
        }

        return response(["message" => "Failed to update password"], 500);
    }
}
