<?php

namespace Flores\RequestManager\Exceptions;

use Exception;

/**
 * Exception thrown when a connection error occurs while making an HTTP request.

 * @author Nelson Flores <nelson.flores@live.com>
 */
class HttpConnectionException extends Exception
{
    public function __construct($message = "Failed to connect to the server", $code = 502, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
