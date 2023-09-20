<?php

namespace App\DTO;

readonly class User implements \JsonSerializable
{
    public function __construct(
        private int $id,
        private string $email,
        private string $firstName,
        private string $lastName,
        private string $avatar
    ) {
    }

    /** @return array<string, int|string> */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'avatar' => $this->avatar,
        ];
    }
}