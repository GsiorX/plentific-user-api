<?php

use App\DTO\PaginatedUser;
use App\DTO\User;
use PHPUnit\Framework\TestCase;

/** @covers App\DTO\PaginatedUser */
class PaginatedUserTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        // Given I have a paginated user
        $user = new PaginatedUser(
            [
                $user1 = new User(
                    1,
                    'John@doe.com',
                    'John',
                    'Doe',
                    'avatar',
                ),
                $user2 = new User(
                    2,
                    'Doe@john.com',
                    'Doe',
                    'John',
                    'avatar2',
                )
            ],
            1,
            4,
            2,
            2,
        );

        // When I serialize the user to JSON
        $json = $user->jsonSerialize();

        // Then I expect the JSON to match the expected format
        $this->assertSame([
            'users' => [
                $user1,
                $user2,
            ],
            'page' => 1,
            'total' => 4,
            'per_page' => 2,
            'total_pages' => 2,
        ], $json);
    }

    public function testHasNextPageIsTrue(): void
    {
        // Given I have a paginated user
        $user = new PaginatedUser(
            [
                new User(1,
                    'John@doe.com',
                    'John',
                    'Doe',
                    'avatar',
                ),
                new User(2,
                    'Doe@john.com',
                    'Doe',
                    'John',
                    'avatar2',
                ),
            ],
            1,
            4,
            2,
            2,
        );

        // When I check if there is a next page
        $hasNextPage = $user->hasNextPage();

        // Then I expect the result to be true
        $this->assertTrue($hasNextPage);
    }

    public function testHasNextPageIsFalse(): void
    {
        // Given I have a paginated user
        $user = new PaginatedUser(
            [
                new User(1,
                    'John@doe.com',
                    'John',
                    'Doe',
                    'avatar',
                ),
                new User(2,
                    'Doe@john.com',
                    'Doe',
                    'John',
                    'avatar2',
                ),
            ],
            1,
            2,
            2,
            1,
        );

        // When I check if there is a next page
        $hasNextPage = $user->hasNextPage();

        // Then I expect the result to be true
        $this->assertFalse($hasNextPage);
    }

    public function testGetNextPageReturnsNextPage(): void
    {
        // Given I have a paginated user
        $user = new PaginatedUser(
            [
                new User(1,
                    'John@doe.com',
                    'John',
                    'Doe',
                    'avatar',
                ),
                new User(2,
                    'Doe@john.com',
                    'Doe',
                    'John',
                    'avatar2',
                ),
            ],
            1,
            4,
            2,
            2,
        );

        // When I request the next page
        $page = $user->nextPage();

        // Then I expect the result to be 2
        $this->assertSame(2, $page);
    }

    public function testGetNextPageReturnsNullWhenNoPageIsAvailable(): void
    {
        // Given I have a paginated user
        $user = new PaginatedUser(
            [
                new User(1,
                    'John@doe.com',
                    'John',
                    'Doe',
                    'avatar',
                ),
                new User(2,
                    'Doe@john.com',
                    'Doe',
                    'John',
                    'avatar2',
                ),
            ],
            1,
            2,
            2,
            1,
        );

        // When I request the next page
        $page = $user->nextPage();

        // Then I expect the result to be 2
        $this->assertNull($page);
    }
}