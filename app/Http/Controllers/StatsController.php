<?php

namespace App\Http\Controllers;

use App\Services\Stats\AnimalStatsService;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;

class StatsController extends Controller
{
    use ApiResponser;
    public function index()
    {
        return $this->successResponse((new AnimalStatsService())->getGeneralStats(), Response::HTTP_OK);
    }
}
