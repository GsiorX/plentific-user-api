<?php

namespace App;

use App\Exceptions\UserNotFoundException;
use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Psr7\Uri;
use App\DTO\CreatedUser;
use App\DTO\PaginatedUser;
use App\DTO\User;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;

readonly class UserApiClient
{
    private const API_DOMAIN = 'https://reqres.in/api/';

    public function __construct(
        private ClientInterface $client,
        private RequestFactoryInterface $requestFactory
    ) {
    }

    public function fetch(int $id): ?User
    {
        $response = $this->client->sendRequest($this->createRequest('users/' . $id, 'GET'));

        if ($response->getStatusCode() === 404) {
            throw new UserNotFoundException();
        }

        $body = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        return new User(
            $body['data']['id'],
            $body['data']['email'],
            $body['data']['first_name'],
            $body['data']['last_name'],
            $body['data']['avatar']
        );
    }

    public function fetchAll(int $page = 1): PaginatedUser
    {
        if ($page < 1) {
            $page = 1;
        }

        $response = $this->client->sendRequest($this->createRequest("users?page=$page", 'GET'));

        $body = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        return new PaginatedUser(
            $body['data'],
            $body['page'],
            $body['total'],
            $body['per_page'],
            $body['total_pages'],
        );
    }

    public function create(string $name, string $job): CreatedUser
    {
        $request = $this->createRequest('users', 'POST');
        $request->getBody()->write(json_encode([
            'name' => $name,
            'job' => $job,
        ], JSON_THROW_ON_ERROR));

        $response = $this->client->sendRequest($request);

        $body = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        return new CreatedUser($body['id']);
    }

    private function createRequest(string $path, string $method): RequestInterface
    {
        $uri = new Uri(self::API_DOMAIN . $path);

        return $this->requestFactory->createRequest($method, $uri);
    }
}