<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    public function index()
    {
        $allCountries = Country::get();
        return response($allCountries, 200);
    }
}
