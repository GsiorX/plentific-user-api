<?php

use App\DTO\User;
use PHPUnit\Framework\TestCase;

/** @covers App\DTO\User */
class UserTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        // Given I have a user
        $user = new User(1, 'John@doe.com', 'John', 'Doe', 'avatar');

        // When I serialize the user to JSON
        $json = $user->jsonSerialize();

        // Then I expect the JSON to match the expected format
        $this->assertSame([
            'id' => 1,
            'email' => 'John@doe.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'avatar' => 'avatar',
        ], $json);
    }
}