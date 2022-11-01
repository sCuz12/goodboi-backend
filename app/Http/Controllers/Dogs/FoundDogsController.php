<?php

namespace App\Http\Controllers\Dogs;

use App\Exceptions\IdNotProvidedException;
use App\Http\Controllers\Controller;
use App\Http\Resources\FoundDogs\FoundDogsResource;
use App\Http\Resources\FoundDogs\SingleFoundDogResource;
use App\Services\Dogs\FoundDogsService;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Response;

class FoundDogsController extends Controller
{
    use ApiResponser;

    protected $foundDogsService;

    public function __construct()
    {
        $this->foundDogsService = (new FoundDogsService());
    }

    public function index()
    {
        $foundDogs = $this->foundDogsService->getAllActiveFoundDogs();
        return  FoundDogsResource::collection($foundDogs);
    }

    public function getSingle($id)
    {
        try {
            $foundDog = $this->foundDogsService->getActiveDogById($id);
        } catch (IdNotProvidedException $e) {
            return $e->render();
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        return new SingleFoundDogResource($foundDog);
    }
}
