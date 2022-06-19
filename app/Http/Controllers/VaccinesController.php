<?php

namespace App\Http\Controllers;

use App\Models\AvailableVaccinations;
use Illuminate\Http\Request;

class VaccinesController extends Controller
{
    public function getAllVaccines()
    {
        return AvailableVaccinations::all();
    }
}
