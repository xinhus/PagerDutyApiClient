<?php

namespace Test\RafaelCardoso\PhpWithGuzzle\Users\UseCases;

use GuzzleHttp\Psr7\Response;
use RafaelCardoso\PhpWithGuzzle\Users\Entities\UserResponse;
use RafaelCardoso\PhpWithGuzzle\Users\UseCases\UserService;
use Test\RafaelCardoso\PhpWithGuzzle\HttpTestCase;

class UserServiceTest extends HttpTestCase
{

    public function testListUsersCanReturnGoodResponse(): void
    {
        $response = new Response(200, [], file_get_contents(__DIR__ . '/StubResponses/ListUsers.json'));
        $_ENV['apiToken'] = 'ApiTokenForTest';
        $this->appendHttpResponseToEndpoint(
            'GET',
            'https://api.pagerduty.com/users',
            ['Authorization' => 'Token token=' . $_ENV['apiToken']],
            $response
        );
        $expectedResponse = new UserResponse($response);

        $service = new UserService();
        $result = $service->getUsers();

        $this->assertEquals($expectedResponse, $result);
        $this->assertIsArray($result->getUsers());
        $this->assertEquals(25 , sizeof($result->getUsers()));
    }

    public function testWithNoResultsHasZeroUsers(): void
    {
        $response = new Response(200, [], file_get_contents(__DIR__ . '/StubResponses/EmptyResult.json'));
        $_ENV['apiToken'] = 'ApiTokenForTest';
        $this->appendHttpResponseToEndpoint(
            'GET',
            'https://api.pagerduty.com/users',
            ['Authorization' => 'Token token=' . $_ENV['apiToken']],
            $response
        );
        $expectedResponse = new UserResponse($response);

        $service = new UserService();
        $result = $service->getUsers();

        $this->assertEquals($expectedResponse, $result);
        $this->assertIsArray($result->getUsers());
        $this->assertEquals(0 , sizeof($result->getUsers()));
    }

    public function testGetResultsFields(): void
    {
        $response = new Response(200, [], file_get_contents(__DIR__ . '/StubResponses/SmallUserList.json'));
        $_ENV['apiToken'] = 'ApiTokenForTest';
        $this->appendHttpResponseToEndpoint(
            'GET',
            'https://api.pagerduty.com/users',
            ['Authorization' => 'Token token=' . $_ENV['apiToken']],
            $response
        );
        $expectedResponse = new UserResponse($response);
        $expectedUsers = [
            [
                'name' => 'Alan B. Shepard, Jr.',
                'id' => 'PLOASXQ',
                'email' => 'alan.shepard@nasa.example',
            ]
        ];


        $service = new UserService();
        $result = $service->getUsers();

        $this->assertEquals($expectedResponse, $result);
        $this->assertEquals($expectedUsers, $result->getUsers());
    }

    public function testGetContactDetails(): void
    {
        $response = new Response(200, [], file_get_contents(__DIR__ . '/StubResponses/SmallUserListWithContact.json'));
        $_ENV['apiToken'] = 'ApiTokenForTest';
        $this->appendHttpResponseToEndpoint(
            'GET',
            'https://api.pagerduty.com/users?query=Alan B. Shepard, Jr.&include[]=contact_methods',
            ['Authorization' => 'Token token=' . $_ENV['apiToken']],
            $response
        );

        $service = new UserService();
        $result = $service->getUser('Alan B. Shepard, Jr.');
        $expectedResult = [
            'name' => 'Alan B. Shepard, Jr.',
            'contacts' => [
                [
                    'type' => 'email_contact_method',
                    'address' => 'konstantin.feoktistov@ussr.example',
                ]
            ]
        ];

        $this->assertEquals($expectedResult, $result);
    }

}