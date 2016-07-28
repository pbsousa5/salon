<?php

namespace App\Libary\Contracts\Http;
use RuntimeException;

class BadAppRequestExceptions extends RuntimeException
{
    private $statusCode;
    
    public function __construct($statusCode, $message = null, \Exception $previous = null, $code = 0)
    {
        $this->statusCode = $statusCode;
        
        parent::__construct($message, $code, $previous);
    }
    
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}