<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Response;

class CreateFoundDogListingException extends Exception
{
    use ApiResponser;

    protected $returnedMessaged;
    public function __construct($e)
    {
        $this->returnedMessaged = $e->getMessage();
    }

    public function report()
    {
        //TODO : LOG
    }

    public function render()
    {
        return $this->errorResponse("Error on create Found dog listing", Response::HTTP_CONFLICT);
    }
}
