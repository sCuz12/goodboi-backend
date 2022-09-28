<?php

namespace App\Http\Interfaces;

use Illuminate\Http\Request;

interface SocialAuthInterface
{
    public function redirectToProvider();
    public function handleProviderCallback(Request $request);
}
