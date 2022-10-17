<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Response;

class ErrorUserRegistrationException extends Exception
{
    use ApiResponser;
    public function report()
    {
        //TODO : Use for reporting
    }

    public function render()
    {
        return $this->errorResponse("Error on User registration", Response::HTTP_CONFLICT);
    }
}
