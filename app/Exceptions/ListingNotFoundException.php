<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Response;

class ListingNotFoundException extends Exception
{
    use ApiResponser;

    public function report()
    {
        //TODO : Use for reporting
    }

    public function render()
    {
        return $this->errorResponse("Listing Not Found", Response::HTTP_NOT_FOUND);
    }
}
