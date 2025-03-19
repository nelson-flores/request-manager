<?php 
namespace Flores\RequestManager\Exceptions;

use Exception;

/**
 * Exception thrown when an invalid content type is used in an HTTP request.
 * @author Nelson Flores <nelson.flores@live.com>
 */
class InvalidContentTypeException extends Exception
{
    public function __construct($message = "Invalid content type", $code = 400, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
