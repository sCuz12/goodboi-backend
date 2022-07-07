<?php

namespace App\Http\Controllers;

use App\Models\Dogs;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FavouriteController extends Controller
{
    public function addToFavourites($id)
    {

        $dog = Dogs::find($id);
        $dog->favourites()->attach(Auth::user()->id);
        return response(["Added to favouirtes"], Response::HTTP_ACCEPTED);
    }

    public function removeFromFavourites($id)
    {
        $dog = Dogs::find($id);
        $dog->favourites()->detach(Auth::user()->id);
        return response(["Removed from favouirtes"], Response::HTTP_ACCEPTED);
    }
}
