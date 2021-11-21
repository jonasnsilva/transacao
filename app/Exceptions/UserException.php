<?php

namespace App\Exceptions;
use Exception;
use Illuminate\Http\Response;

class UserException extends Exception
{
    public function __construct($message, $code = Response::HTTP_UNPROCESSABLE_ENTITY) {
        parent::__construct($message, $code);
    }
}
