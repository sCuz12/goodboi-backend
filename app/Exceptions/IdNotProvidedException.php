<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Response;

class IdNotProvidedException extends Exception
{
    use ApiResponser;

    public function report()
    {
        //TODO : LOG
    }

    public function render()
    {
        return $this->errorResponse("ID not provided", Response::HTTP_BAD_REQUEST);
    }
}
