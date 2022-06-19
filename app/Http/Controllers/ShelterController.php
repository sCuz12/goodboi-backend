<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShelterSingleResource;
use App\Models\Shelter;
use Illuminate\Http\Request;

class ShelterController extends Controller
{
    public function getSingle($id)
    {
        $shelter = Shelter::findById($id);
        return new ShelterSingleResource($shelter);
    }
}
