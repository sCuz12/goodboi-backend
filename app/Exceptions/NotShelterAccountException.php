<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Response;

class NotShelterAccountException extends Exception
{
    use ApiResponser;

    public function report()
    {
        //TODO : Use for reporting
    }

    public function render()
    {
        return $this->errorResponse("Not a shelter account type", Response::HTTP_UNAUTHORIZED);
    }
}
