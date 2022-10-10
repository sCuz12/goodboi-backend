<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Response;

class NotListingOwnerException extends Exception
{
    use ApiResponser;

    public function report()
    {
        //TODO : Use for reporting
    }

    public function render()
    {
        return $this->errorResponse("Not owner of this listing", Response::HTTP_UNAUTHORIZED);
    }
}
