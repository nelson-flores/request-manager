# HttpRequestManager

A PHP library for managing HTTP requests using cURL.

## 📞 Installation

You can install this library via Composer:

```sh
composer require flores/request-manager
```

## 🚀 Usage

### Creating a Request

```php
use Flores\RequestManager\Requests\GetRequest;

$request = new GetRequest('https://yoururl.com/api/resource');
$response = $request->send();

echo $response->getDataString();
```

### Setting Headers

```php
$request->setHeader('Authorization', 'Bearer token')
        ->setHeader('Accept', 'application/json');
```

### Setting Authentication

```php
$request->setBearerToken('your_token_here'); // Uses Bearer Token authentication
$request->setAuth('username', 'password');   // Uses Basic Auth
```

### Sending JSON Data (POST)

```php
use Flores\RequestManager\Requests\PostRequest;

$request = new PostRequest('https://yoururl.com/api/resource');
$request->setJsonBody([
    'title' => 'New Post',
    'body' => 'Post content',
    'userId' => 1
]);
$response = $request->send();

echo $response->getData();
```

### Retrieving Response Data

```php
echo $response->getData();       // Returns the processed response (object)
echo $response->getDataString(); // Returns the processed response as JSON String
```

### Getting the Raw cURL Response

If you need the raw response from the cURL request, use `getResponse()`:

```php
echo $response->get(); // Returns the raw response as a string
```

### Available Methods

#### Request Methods

- `setUrl($url)`: Sets the request URL.
- `setHeader($key, $value)`: Sets an HTTP header.
- `setHeaders(array $headers)`: Sets multiple headers at once.
- `setPayloads(array $payloads)`: Sets the request body.
- `setJsonBody(array $data)`: Sets the request body as JSON.
- `setBearerToken($token)`: Sets the Authorization header using Bearer Token.
- `setAuth($user, $passwd)`: Sets Basic Authentication credentials.
- `setContentType($contentType)`: Sets the request content type.
- `setCurlOption($key, $value)`: Sets a custom cURL option.
- `getUrl()`: Returns the request URL.
- `getPayloads()`: Returns the data sent in the request.
- `getContentType()`: Returns the defined content type.
- `send()`: Executes the request and returns an `HttpResponse` object.

#### Response Methods

- `get()`: Returns the raw cURL response.
- `getData()`: Returns the structured response (object).
- `getDataString()`: Returns the response in JSON format.

### Example of a DELETE Request

```php
use Flores\RequestManager\Requests\DeleteRequest;

$request = new DeleteRequest('https://yoururl.com/api/resource/1');
$response = $request->send();

echo $response->getDataString();
```

### Error Handling

```php
try {
    $request = new PostRequest('https://yoururl.com/api/resource');
    $request->setPayloads(['title' => 'Error Test']);
    $response = $request->send();
    echo $response->getData();
} catch (HttpRequestException $e) {
    echo "Request error: " . $e->getMessage();
} catch (HttpConnectionException $e) {
    echo "Connection error: " . $e->getMessage();
}
```

## ⚠️ Handled Exceptions

- `InvalidContentTypeException`: Invalid content type.
- `HttpRequestException`: HTTP request error.
- `HttpConnectionException`: Server connection failure.

## 📄 License

This project is licensed under the [MIT License](LICENSE).
