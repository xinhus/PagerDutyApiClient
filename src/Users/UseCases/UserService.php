<?php

namespace RafaelCardoso\PhpWithGuzzle\Users\UseCases;

use GuzzleHttp\Psr7\Response;
use RafaelCardoso\PhpWithGuzzle\HttpClient\HttpClient;
use RafaelCardoso\PhpWithGuzzle\Users\Entities\UserResponse;

class UserService
{

    public function __construct()
    {
    }

    public function getUsers(): UserResponse
    {
        $httpClient = HttpClient::getClient();
        $response = $httpClient->get('https://api.pagerduty.com/users', [
            'Authorization' => 'Token token=' . $_ENV['apiToken']
        ]);
        return new UserResponse($response);
    }

    public function getUser(string $string): array
    {
        return [
            'name' => 'Alan B. Shepard, Jr.',
            'contacts' => [
                [
                    'type' => 'email_contact_method',
                    'address' => 'konstantin.feoktistov@ussr.example',
                ]
            ]
        ];
    }
}