<?php

use App\DTO\User;
use App\UserApiClient;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

/** @covers \App\UserApiClient */
class UserApiClientTest extends TestCase
{
    public function testFetch(): void
    {
        // Given I have a client
        $client = $this->createMock(ClientInterface::class);

        // And a request factory
        $requestFactory = $this->createMock(RequestFactoryInterface::class);

        // Then I expect the request factory to create a request
        $requestFactory->expects($this->atLeast(1))
            ->method('createRequest')
            ->with('GET', 'https://reqres.in/api/users/1');

        // And the client to send the request
        $client->expects($this->atLeast(1))
            ->method('sendRequest')
            ->willReturn($response = new \GuzzleHttp\Psr7\Response(200, [], json_encode([
                'data' => [
                    'id' => 1,
                    'email' => 'abc@abc.com',
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'avatar' => 'avatar',
                ]
            ])));

        // When I fetch a user
        $sut = new UserApiClient($client, $requestFactory);
        /** @var User $user */
        $user = $sut->fetch(1);

        // Then I expect the user to be returned
        $this->assertSame(1, $user->jsonSerialize()['id']);
        $this->assertSame('abc@abc.com', $user->jsonSerialize()['email']);
        $this->assertSame('John', $user->jsonSerialize()['first_name']);
        $this->assertSame('Doe', $user->jsonSerialize()['last_name']);
        $this->assertSame('avatar', $user->jsonSerialize()['avatar']);
    }

    public function testFetchAll(): void
    {
        // Given I have a client
        $client = $this->createMock(ClientInterface::class);

        // And a request factory
        $requestFactory = $this->createMock(RequestFactoryInterface::class);

        // Then I expect the request factory to create a request
        $requestFactory->expects($this->atLeast(1))
            ->method('createRequest')
            ->with('GET', 'https://reqres.in/api/users?page=1');

        // And the client to send the request
        $client->expects($this->atLeast(1))
            ->method('sendRequest')
            ->willReturn(new \GuzzleHttp\Psr7\Response(200, [], json_encode([
                    'data' => [
                        [
                            'id' => 1,
                            'email' => 'abc@abc.com',
                            'first_name' => 'John',
                            'last_name' => 'Doe',
                            'avatar' => 'avatar',
                        ],
                        [
                            'id' => 2,
                            'email' => 'info@example.com',
                            'first_name' => 'Doe',
                            'last_name' => 'Joe',
                            'avatar' => 'avatar2',
                        ],
                    ],
                    'page' => 1,
                    'per_page' => 2,
                    'total' => 3,
                    'total_pages' => 2,
                ])
            ));

        // When I fetch a user
        $sut = new UserApiClient($client, $requestFactory);
        $paginatedUser = $sut->fetchAll();

        // Then I expect the paginator to have the correct data
        $this->assertSame(1, $paginatedUser->jsonSerialize()['page']);
        $this->assertSame(2, $paginatedUser->jsonSerialize()['per_page']);
        $this->assertSame(3, $paginatedUser->jsonSerialize()['total']);
        $this->assertSame(2, $paginatedUser->jsonSerialize()['total_pages']);

        // And I expect users to be returned
        $users = $paginatedUser->jsonSerialize()['users'];

        $this->assertSame(1, $users[0]['id']);
        $this->assertSame('abc@abc.com', $users[0]['email']);
        $this->assertSame('John', $users[0]['first_name']);
        $this->assertSame('Doe', $users[0]['last_name']);
        $this->assertSame('avatar', $users[0]['avatar']);

        $this->assertSame(2, $users[1]['id']);
        $this->assertSame('info@example.com', $users[1]['email']);
        $this->assertSame('Doe', $users[1]['first_name']);
        $this->assertSame('Joe', $users[1]['last_name']);
        $this->assertSame('avatar2', $users[1]['avatar']);
    }

    public function testCreate(): void
    {
        // Given I have a client
        $client = $this->createMock(ClientInterface::class);

        // And a request factory
        $requestFactory = $this->createMock(RequestFactoryInterface::class);

        // Then I expect the request factory to create a request
        $requestFactory->expects($this->atLeast(1))
            ->method('createRequest')
            ->with('POST', 'https://reqres.in/api/users');

        // And the client to send the request
        $client->expects($this->atLeast(1))
            ->method('sendRequest')
            ->with()
            ->willReturn($response = new \GuzzleHttp\Psr7\Response(200, [], json_encode([
                    'id' => 1,
                    'job' => 'engineer',
                    'name' => 'John',
                    'createdAt' => '2023-09-20T14:48:22.034Z',
            ])));

        // When I fetch a user
        $sut = new UserApiClient($client, $requestFactory);
        $user = $sut->create('John', 'engineer');

        // Then I expect the user id to be returned
        $this->assertSame(1, $user->jsonSerialize()['id']);
    }
}