<?php
namespace Flores\RequestManager\Requests;
use Flores\RequestManager\Core\HttpRequest;
use Flores\RequestManager\Response\HttpResponse;

/**
 * HTTP GET request class.
 * @author Nelson Flores <nelson.flores@live.com>
 */
class HeadRequest extends HttpRequest
{
    public function send(): HttpResponse
    {
        $this->setCurlOption(CURLOPT_CUSTOMREQUEST, 'HEAD');
        return $this->executeRequest();
    }
}
