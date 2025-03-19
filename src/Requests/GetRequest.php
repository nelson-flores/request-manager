<?php
namespace Flores\RequestManager\Requests;
use Flores\RequestManager\Core\HttpRequest;
use Flores\RequestManager\Response\HttpResponse;

/**
 * HTTP GET request class.
 * @author Nelson Flores <nelson.flores@live.com>
 */
class GetRequest extends HttpRequest
{
    public function send(): HttpResponse
    {
        $this->setCurlOption(CURLOPT_HTTPGET, true);
        return $this->executeRequest();
    }
}
