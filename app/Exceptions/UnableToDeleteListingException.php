<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Response;

class UnableToDeleteListingException extends Exception
{
    use ApiResponser;

    public function report()
    {
        //TODO : Use for reporting
    }

    public function render()
    {
        return $this->errorResponse("Error on deleting the listing ", Response::HTTP_UNAUTHORIZED);
    }
}
