<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class TransactionException extends Exception
{
    public function __construct($message, $code = Response::HTTP_BAD_REQUEST) {
        parent::__construct($message, $code);
    }
}
