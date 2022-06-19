<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function getCitiesCountry($id)
    {
        return City::getCityByCountryId($id);
    }

    public function getAllCities()
    {
        return City::all();
    }
}
