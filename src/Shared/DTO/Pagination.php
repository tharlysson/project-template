<?php

declare(strict_types=1);

namespace POS\Shared\DTO;

final readonly class Pagination
{
    public function __construct(
        public int $total,
        public int $perPage,
        public int $currentPage,
        public int $lastPage,
    ) {
    }
}