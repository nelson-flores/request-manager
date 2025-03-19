<?php

namespace Flores\RequestManager\Core;

use Flores\RequestManager\Exceptions\InvalidContentTypeException;
use Flores\RequestManager\Exceptions\HttpRequestException;
use Flores\RequestManager\Exceptions\HttpConnectionException;
use Flores\RequestManager\Response\HttpResponse;

/**
 * Abstract class for handling HTTP requests using cURL.
 * Provides a base structure for different HTTP methods.
 *
 * @author Nelson Flores <nelson.flores@live.com>
 */
abstract class HttpRequest
{
    // Supported HTTP schemes
    public static $SCHEME_HTTPS = "https";
    public static $SCHEME_HTTP = "http";
    public static $SCHEME_FTPS = "ftps";
    public static $SCHEME_FTP = "ftp";

    // Supported HTTP methods
    public static $METHOD_GET = "GET";
    public static $METHOD_POST = "POST";
    public static $METHOD_PUT = "PUT";
    public static $METHOD_HEAD = "HEAD";
    public static $METHOD_DELETE = "DELETE";
    public static $METHOD_CONNECT = "CONNECT";
    public static $METHOD_OPTIONS = "OPTIONS";
    public static $METHOD_TRACE = "TRACE";
    public static $METHOD_PATCH = "PATCH";

    protected $ch; // cURL handler
    protected $headers = []; // Request headers
    protected $curlOptions = []; // cURL options
    protected $method; // HTTP method (GET, POST, etc.)
    protected $scheme; // Protocol (http or https)
    protected $url = null; // Request URL
    protected $payloads = []; // Data payload
    protected $contentType; // Content type of the request
    protected $error = null; // Error message, if any

    /**
     * Constructor initializes cURL and sets default options.
     *
     * @param string|null $url Optional request URL.
     */
    public function __construct($url = null)
    {
        $this->scheme = 'http';
        $this->setContentType('text/html');
        $this->ch = curl_init();
        $this->setCurlOption(CURLOPT_TIMEOUT_MS, 30000);
        $this->followRedirects()->setReturnTransfer()->setUrl($url);
    }

    /**
     * Abstract method that must be implemented in subclasses to send the request.
     */
    abstract public function send();

    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function setHeaders(array $headers)
    {
        foreach ($headers as $key => $value) {
            $this->setHeader($key, $value);
        }
        return $this;
    }

    /**
     * Enables automatic redirection handling.
     *
     * @return $this
     */
    public function followRedirects()
    {
        $this->setCurlOption(CURLOPT_FOLLOWLOCATION, true);
        return $this;
    }

    /**
     * Ensures cURL returns the response as a string instead of outputting it directly.
     *
     * @return $this
     */
    public function setReturnTransfer()
    {
        $this->setCurlOption(CURLOPT_RETURNTRANSFER, true);
        return $this;
    }

    /**
     * Sets the request URL after validating it.
     *
     * @param string|null $url The request URL.
     * @return $this
     * @throws \InvalidArgumentException If the URL is invalid.
     */
    public function setUrl($url)
    {
        if ($url !== null) {
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                throw new \InvalidArgumentException("Invalid URL provided.");
            }
            $this->url = $url;
            $this->setCurlOption(CURLOPT_URL, $url);
        }
        return $this;
    }


    public function setMethod($method)
    {
        $this->method = strtoupper($method);
        return $this;
    }

    public function post()
    {
        $this->setMethod(self::$METHOD_POST)->send();
        return $this;
    }



    public function getUrl()
    {
        return $this->getHandledUrl();
    }


    /**
     * @return string
     */
    public function getHost()
    {
        return explode("/", $this->url)[0];
    }




    /**
     * @return string
     */
    public function getPath()
    {
        $path = "/";

        $arr = explode("/", $this->url);
        if (isset($arr[1])) {
            unset($arr[0]);
            $path = "/" . implode("/", $arr);
        }

        return $path;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /*
    * @return array
    */
    public function getPayloads()
    {
        return $this->payloads;
    }

    /*
    * @return array
    */
    public function getCh()
    {
        return $this->ch;
    }

    /**
     * @return string
     */
    public function getRelativeUrl()
    {
        return $this->url;
    }


    private function getHandledUrl()
    {
        $url = $this->url;
        $arr = explode("://", $url);

        if (count($arr ?? []) > 1) {
            $this->scheme = $arr[0];
            $url = $arr[1];
        }

        $url = trim($url, "/");
        $qs = explode("?", $url);

        if (isset($qs[1])) {
            $url = $qs[0];
            parse_str($qs[1], $querytr);
            $this->setPayloads($querytr);
        }

        $this->url = $url;
        $url = $this->scheme . "://" . $this->url;

        if ($this->method === self::$METHOD_GET) {
            $queryString = http_build_query($this->payloads);
            $url .= "?" . $queryString;
        }

        return $url;
    }

    public function getScheme()
    {
        return $this->scheme;
    }

    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

    public function setCurlOption($option, $value)
    {
        $this->curlOptions[$option] = $value;
        return $this;
    }

    public function setPayloads(array $payloads)
    {
        $this->payloads = $payloads;
        return $this;
    }

    public function executeRequest()
    {
        if (!$this->url) {
            throw new HttpRequestException("No URL specified for the request.");
        }

        $this->handleCurlOptions();
        $response = curl_exec($this->ch);
        $httpCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        $error = curl_error($this->ch);

        if ($response === false) {
            throw new HttpConnectionException("cURL Error: " . $error);
        }

        return new HttpResponse($this, $response, $error, $httpCode);
    }

    private function handleCurlOptions()
    {
        foreach ($this->curlOptions as $key => $value) {
            curl_setopt($this->ch, $key, $value);
        }
    }

    public function __destruct()
    {
        curl_close($this->ch);
    }
}
