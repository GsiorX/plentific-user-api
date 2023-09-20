<?php

use App\DTO\CreatedUser;
use PHPUnit\Framework\TestCase;

/** @covers App\DTO\CreatedUser */
class CreatedUserTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        // Given I have a created user
        $user = new CreatedUser(1);

        // When I serialize the user to JSON
        $json = $user->jsonSerialize();

        // Then I expect the JSON to match the expected format
        $this->assertSame(['id' => 1], $json);
    }
}