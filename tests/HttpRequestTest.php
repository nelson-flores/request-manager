<?php

use PHPUnit\Framework\TestCase;
use Flores\RequestManager\Requests\GetRequest;
use Flores\RequestManager\Requests\PostRequest;
use Flores\RequestManager\Exceptions\HttpConnectionException;

class HttpRequestTest extends TestCase
{
    private string $validUrl = 'https://jsonplaceholder.typicode.com/posts/1';
    private string $invalidUrl = 'http://invalid-url.test';

    public function testGetRequestReturnsResponse()
    {
        $request = new GetRequest($this->validUrl);
        $response = $request->send();

        $this->assertNotEmpty($response, 'Response should not be empty');
    }
    public function testGetRequestReturnsResponseData()
    {
        $request = new GetRequest($this->validUrl);
        $response = $request->send();

        $this->assertNotEmpty($response->getData(), 'Response should not be empty');
    }

    public function testPostRequestWithPayload()
    {
        $request = new PostRequest($this->validUrl);
        $request->setPayloads(['title' => 'Test', 'body' => 'This is a test']);

        $response = $request->send();

        $this->assertNotEmpty($response, 'Response should not be empty');
    }

    public function testInvalidUrlThrowsException()
    {
        $this->expectException(HttpConnectionException::class);

        $request = new GetRequest($this->invalidUrl);
        $request->send();
    }
}
