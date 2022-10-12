<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Response;

class IncorrectListingTypeException extends Exception
{
    use ApiResponser;

    public function report()
    {
        //TODO : LOG
    }

    public function render()
    {
        return $this->errorResponse("Incorrect listing type", Response::HTTP_CONFLICT);
    }
}
