<?php

namespace Test\RafaelCardoso\PhpWithGuzzle;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RafaelCardoso\PhpWithGuzzle\HttpClient\HttpClient;

class HttpTestCase extends TestCase
{

    private MockHandler $mockHandler;

    public function setUp(): void
    {
        parent::setUp();
        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $httpClient = new Client(['handler' => $handlerStack]);
        HttpClient::setClient($httpClient);
    }

    protected function appendHttpResponseToEndpoint(string $method, string $endpoint, array $options, Response $response): void
    {
        $this->mockHandler->append(function(Request $request, array $headers) use ($method, $endpoint, $options, $response) {
            if ($request->getMethod() !== $method ) {
                return new \LogicException('Invalid Request Method. Expected: '. $method . ' but received: ' . $request->getMethod());
            }

            if ($request->getUri() != $endpoint ) {
                return new \LogicException('Invalid Request URI. Expected: '. $endpoint . ' but received: ' . $request->getUri());
            }

            foreach ($options as $headerToCheck => $value) {
                if (array_key_exists($headerToCheck, $headers) === false ) {
                    return new \LogicException('Missing Required header Expected: '. $headerToCheck );
                }
                if ($headers[$headerToCheck] != $value ) {
                    return new \LogicException('Worng Header value for '. $headerToCheck . ' Expected: '. $value . ' but received: ' . $headers[$headerToCheck] );
                }
            }

            return $response;
        });
    }

    protected function appendHttpException(RequestException $exception): void
    {
        $this->mockHandler->append($exception);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        if ($this->mockHandler->count() != 0) {
            $this->fail('Queued Response not consumed');
        }
    }


}