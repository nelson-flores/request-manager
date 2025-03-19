<?php 
namespace Flores\RequestManager\Requests;
use Flores\RequestManager\Core\HttpRequest;
use Flores\RequestManager\Response\HttpResponse;
/**
 * HTTP POST request class.
 * @author Nelson Flores <nelson.flores@live.com>
 */
class PostRequest extends HttpRequest
{
    public function send(): HttpResponse
    {
        $this->setCurlOption(CURLOPT_POST, true);
        $this->setCurlOption(CURLOPT_POSTFIELDS, http_build_query($this->payloads));
        return $this->executeRequest();
    }
}