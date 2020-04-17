# <a id="title" href="#">Surplex API Mock</a>

A software to mock third-party responses (Mostly used for automated testing). To use the mock, send a request to the mock with sample data and get returned when you 
request an external service, when you configured your settings appropriately.

&nbsp;
&nbsp;
## <a id="index" href="#index">Index</a>

* **[Surplex API Mock](#title)**
* **[Index](#index)**
* **[Requirements](#requirements)**
* **[Tutorial](#tutorial)**
* **[Setup](#setup)**
* **[Reserved urls / Default responses](#reserved_urls)**
* **[Commands](#commands)**
* **[Tests](#tests)**

&nbsp;
&nbsp;
## <a id="requirements" href="#requirements">Requirements</a>

### Software
* Docker
* Docker-Compose
* GNU-Make (optional, recommend)

### Ports

Docker assigns automatically an open port. To see the assigned port use `$ docker ps`.

&nbsp;
&nbsp;
## <a id="tutorial" href="#tutorial">Tutorial</a>

### Create a mock / Save a response

    POST [url]?session_id=[session_id]
    {
        "status_code": 200,
        "headers": { ... },
        "data": "...",
        "order": 0,
        "request_key": "key_to_save_request"
    }

`$ curl -X POST -d "data: {[data]}, headers: [headers], status_code: [status_code]}" [url]?session_id=[session_id]`
* **url:** *URL to API Mock*
* **session_id:** *Identification for the Mock Collection. If no session_id is specified as specified below then a session_id will be generated and returned.* | *optional*
* **status_code:** *Status-Code of the Response. See [RFC7231](https://tools.ietf.org/html/rfc7231#section-6.1)*
* **headers:** *Response Header*
* **data:** *Response Body*
* **order:** *Order of the Response within the session. Responses with a lower order are returned first.* | *optional (default=0)*
* **request_key** *If given, the request of the client is stored and can be retrieved via the ```/client-request``` endpoint* | *optional (default=null, request is not stored)*

#### Response

    HTTP/1.1 201 Created

    [session_id]

### Count of mocks, which are not sent

A GET request to the root URL of the API mock will return the count of unsent
mock responses for the given session.

    GET [url]?session_id=[session_id]

`$ curl [url]?session_id=[session_id]`
* **url:** *URL to API Mock*
* **session_id:** *Identification for the Mock Collection* | **required**

#### Response

    HTTP/1.1 200 Ok
    
    [count]

### Receive mock

Every request that does not match any of the patterns mentioned here will return the next
mock response.

`$ curl -X [method] -d [data] [url]`
* **url:** *URL to API Mock *plus* whatever you want, but it must not match any of the patterns above*
* **method:** *Request method* | *optional*
* **data:** *Request data* | *optional*

**Example:** *POST http://api-mock.local/my/request*

**SESSION ID REQUIRED**

#### Response
*[specified in mock]*

#### Information
**SESSION ID REQUIRED**: **The session_id must be transferred.**

### Retrieve stored client request

If you specify a `request_key` for a mock response, the request triggering this
response is stored and can be retrieved via this endpoint for additional checks. If
the same request_key is used multiple times, the endpoint will return the requests
in chronological order until all requests have been retrieved (then you get a 404).

    GET /client-request?session_id=[session_id]&request_key=[request_key]
    
#### Response

    HTTP/1.1 200 Ok
    Content-Type: application/json
    
    {
        "method": "GET",
        "url": "/api/v1/call?param=value",
        "header": { ... },
        "body": "This is the body of the request"
    }

### Clear session

Calling this endpoint will remove all unsent mock responses and all
stored client requests, that were not retrieved, yet. Use this at the end
of your tests to clean up.

    POST /clear-session?session_id=[session_id]

#### Response

    HTTP/1.1 204 No content

&nbsp;
&nbsp;
## <a id="setup" href="#setup">Setup</a>

### Available make commands:
* dev  *Starts the necessary containers and uses a volume on the working directory.*
&nbsp;
* prod  *Starts the necessary containers*
&nbsp;
* test  *Starts the necessary containers, which are not open to the outside (ports)*
&nbsp;
* clean  *Cleans the desk*
&nbsp;
### For local environment (with make):
* **Run tests**  `$ make test`
&nbsp;
* **Start api-mock**  `$ make dev|prod|test`
&nbsp;
* **Stop api-mock**  `$ make clean`
## <a id="reserved_urls" href="#reserved_urls">Reserved urls / Default responses</a>
---  
We have introduced reserved urls or default responses to send an already existing response for a certain URL without registering the response before.

To take the advantage of reserved urls, insert the following code before the `Application::run()` call in the **web/index.php**.
```php
Yii::createObject(Srplx\Mock\Service\ReservedUrlService::class)->addUrl(
    new \Srplx\Mock\Component\ReservedUrl(
        'place your regex here for matching the url',
        function (Request $request, Response $response) {
            $mock = new \Srplx\Mock\Model\Mock();
            ...
            Yii::createObject(\Srplx\Mock\Service\ResponseService::class)->createResponse($response, $mock)->send();
        }
    )
);
```

## <a id="commands" href="#commands">Commands</a>

* **Build containers**  `$ docker-compose -f docker-compose.yml -f docker-compose.[dev|prod|test].yml build`
* **Stop containers**  `$ docker-compose down`
* **List all containers**  `$ docker ps` or `$ docker-compose ps `
&nbsp;
&nbsp;
## <a id="tests" href="#tests">Tests</a>

Following tests are available:
* **API Test** ```$ ./bin/codecept run api```
* **Unit Test** ```$ ./bin/codecept run unit```
