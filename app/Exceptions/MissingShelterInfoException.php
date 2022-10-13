<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Response;

class MissingShelterInfoException extends Exception
{
    use ApiResponser;

    public function report()
    {
        //TODO : Use for reporting
    }

    public function render()
    {
        return $this->errorResponse("Shelter information are missing ! Update Shelter information", Response::HTTP_UNAUTHORIZED);
    }
}
