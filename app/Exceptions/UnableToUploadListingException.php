<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Response;

class UnableToUploadListingException extends Exception
{
    use ApiResponser;

    public function report()
    {
        //TODO : Use for reporting
    }

    public function render()
    {
        return $this->errorResponse("Uploading listing failed!", Response::HTTP_UNAUTHORIZED);
    }
}
