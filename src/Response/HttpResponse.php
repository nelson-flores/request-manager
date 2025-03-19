<?php

namespace Flores\RequestManager\Response;

/**
 * Class representing an HTTP response.
 * Provides structured access to response data, errors, and metadata.
 * 
 * @author Nelson Flores <nelson.flores@live.com>
 */
class HttpResponse
{
    private $request; // Instance of the HTTP request class
    private $response; // Raw response content
    private $error; // Error message if the request failed
    private $httpCode; // HTTP status code

    /**
     * Constructor initializes response data.
     *
     * @param object $request  The request object
     * @param string $response The raw response data
     * @param string|null $error The error message, if any
     * @param int|null $httpCode The HTTP response code
     */
    public function __construct($request, $response, $error = null, $httpCode = null)
    {
        $this->request = $request;
        $this->response = $response;
        $this->error = $error;
        $this->httpCode = $httpCode;
    }

    /**
     * Get the raw response content.
     *
     * @return string
     */
    public function get()
    {
        return $this->response;
    }

    /**
     * Get the error message if any.
     *
     * @return string|null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Check if the response content is valid JSON.
     *
     * @param string $string The response content
     * @return bool True if valid JSON, false otherwise
     */
    private function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Get the HTTP status code.
     *
     * @return int|null
     */
    public function getStatusCode()
    {
        return $this->httpCode;
    }

    /**
     * Get structured response data including request metadata.
     *
     * @return object Response data as an object
     */
    public function getData()
    {
        $response = $this->get();

        // Decode response if it's JSON
        try {
            if ($this->isJson($response)) {
                $response = json_decode($response);
            }
        } catch (\Throwable $th) {
            // Handle JSON decoding failure silently
        }

        // Collect request metadata
        $data = [
            'url' => $this->request->getUrl(),
            'scheme' => $this->request->getScheme(),
            'host' => $this->request->getHost(),
            'relative_url' => $this->request->getRelativeUrl(),
            'path' => $this->request->getPath(),
            'method' => $this->request->getMethod(),
            'content_type' => $this->request->getContentType(),
            'payloads' => $this->request->getPayloads(),
            'response' => $this->response,
            'error' => $this->error,
        ];

        // Retrieve cURL request info if available
        if ($this->response !== null) {
            $infos = curl_getinfo($this->request->getCh());
            foreach ($infos as $key => $value) {
                $data[$key] = ($value === "" || $value === null) ? (empty($data[$key]) ? null : $data[$key]) : $value;
            }
        }

        // Convert data to JSON and back to an object for consistency
        try {
            $data = json_encode($data);
            $data = json_decode($data);
            $data->response = $response;
        } catch (\Throwable $th) {
            $data->response = $response;
        }

        return $data;
    }
    
    /**
     * Get structured response data including request metadata.
     *
     * @return string Response data as string
     */
    public function getDataString()
    {
        return json_encode($this->getData());
    }

}
