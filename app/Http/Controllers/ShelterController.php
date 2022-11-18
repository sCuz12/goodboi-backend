<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShelterSingleResource;
use App\Repositories\ShelterRepository;

class ShelterController extends Controller
{
    public function getSingle($id)
    {
        $shelter = ShelterRepository::getShelterById($id);
        return new ShelterSingleResource($shelter);
    }
}
