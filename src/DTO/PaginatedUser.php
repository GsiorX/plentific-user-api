<?php

namespace App\DTO;

readonly class PaginatedUser implements \JsonSerializable
{
    /**
     * @param array<User> $users
     */
    public function __construct(
        private array $users,
        private int $page,
        private int $total,
        private int $perPage,
        private int $totalPages,
    ) {
    }

    public function hasNextPage(): bool
    {
        return $this->page < $this->totalPages;
    }

    public function nextPage(): ?int
    {
        return $this->hasNextPage() ? $this->page + 1 : null;
    }

    /** @return array<string, array<User>|int> */
    public function jsonSerialize(): array
    {
        return [
            'users' => $this->users,
            'page' => $this->page,
            'total' => $this->total,
            'per_page' => $this->perPage,
            'total_pages' => $this->totalPages,
        ];
    }
}