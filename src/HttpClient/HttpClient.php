<?php

namespace RafaelCardoso\PhpWithGuzzle\HttpClient;

use GuzzleHttp\Client;

class HttpClient
{
    private static ?Client $client;

    public static function getClient(): Client
    {
        return self::$client ?? new Client();
    }

    public static function setClient(Client $customClient): void
    {
        self::$client = $customClient;
    }

    public static function reset()
    {
        self::$client = null;
    }
}