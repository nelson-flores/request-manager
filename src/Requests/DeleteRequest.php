<?php 
namespace Flores\RequestManager\Requests;
use Flores\RequestManager\Core\HttpRequest;
use Flores\RequestManager\Response\HttpResponse;
/**
 * HTTP PUT request class.
 * @author Nelson Flores <nelson.flores@live.com>
 */
class DeleteRequest extends HttpRequest
{
    public function send(): HttpResponse
    {
        $this->setCurlOption(CURLOPT_CUSTOMREQUEST, "DELETE");
        $this->setCurlOption(CURLOPT_POSTFIELDS, http_build_query($this->payloads));
        return $this->executeRequest();
    }
}