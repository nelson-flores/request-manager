<?php

namespace Flores\RequestManager\Exceptions;

use Exception;

/**
 * Exception thrown when an error occurs during an HTTP request.

 * @author Nelson Flores <nelson.flores@live.com>
 */
class HttpRequestException extends Exception
{
    public function __construct($message = "HTTP request error", $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
