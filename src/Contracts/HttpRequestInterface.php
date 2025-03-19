<?php

namespace Flores\RequestManager\Contracts;

use Flores\RequestManager\Exceptions\InvalidContentTypeException;
use Flores\RequestManager\Exceptions\HttpRequestException;
use Flores\RequestManager\Exceptions\HttpConnectionException;

/**
 * Interface for handling HTTP requests.
 * 
 * Defines the contract for any HTTP request class, ensuring consistent 
 * implementation across different request types (GET, POST, etc.).

 * @author Nelson Flores <nelson.flores@live.com>
 */
interface HttpRequestInterface
{
    /**
     * Sends the HTTP request and returns the response.
     *
     * @return mixed The response data.
     * @throws HttpConnectionException If there is a network issue.
     * @throws HttpRequestException If the request fails.
     */
    public function send();

    /**
     * Sets the request URL.
     *
     * @param string $url The URL to which the request will be sent.
     * @return self
     */
    public function setUrl($url);

    /**
     * Sets the request headers.
     *
     * @param array $headers Associative array of headers (e.g., ["Content-Type" => "application/json"]).
     * @return self
     */
    public function setHeaders(array $headers);

    /**
     * Sets the request payload (data to be sent in the body).
     *
     * @param array $payloads Associative array containing the request payload.
     * @return self
     */
    public function setPayloads(array $payloads);

    /**
     * Sets the request's content type.
     *
     * @param string $contentType The MIME type of the request (e.g., "application/json").
     * @return self
     * @throws InvalidContentTypeException If the content type is invalid.
     */
    public function setContentType($contentType);
}
