<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{

    /**
     * Get the All locations 
     *
     * @return void
     */
    public function getAllLocations()
    {
        //TODO 
    }


    /**
     * Get the All locations 
     *
     * @return void
     */
    public function getLocationsByCity(int $city_id)
    {
        return Location::getLocationsByCity($city_id);
    }
}
