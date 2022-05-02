<?php

namespace RafaelCardoso\PhpWithGuzzle\Users\Entities;

use GuzzleHttp\Psr7\Response;

class UserResponse
{

    private Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function getUsers(): array
    {
        $responseBody = json_decode((string)$this->response->getBody(), true);

        $users = [];
        foreach ($responseBody['users'] as $user) {
            array_push($users, [
                'name' => $user['name'],
                'id' => $user['id'],
                'email' => $user['email'],
            ]);
        }

        return $users;
    }
}