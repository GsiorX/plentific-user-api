<?php

namespace App\DTO;

readonly class CreatedUser implements \JsonSerializable
{
    public function __construct(private int $id)
    {
    }

    /** @return array<string, int> */
    public function jsonSerialize(): array
    {
        return ['id' => $this->id];
    }
}