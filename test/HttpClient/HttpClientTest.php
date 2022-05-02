<?php

namespace Test\RafaelCardoso\PhpWithGuzzle\HttpClient;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use RafaelCardoso\PhpWithGuzzle\HttpClient\HttpClient;

class HttpClientTest extends TestCase
{

    public function testSetClient(): void
    {
        $customClient = new Client();
        HttpClient::setClient($customClient);

        $this->assertSame($customClient, HttpClient::getClient());
    }

    public function testResetClient(): void
    {
        $customClient = new Client();
        HttpClient::setClient($customClient);
        HttpClient::reset();

        $this->assertNotSame($customClient, HttpClient::getClient());
    }
}