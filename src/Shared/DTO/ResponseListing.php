<?php

declare(strict_types=1);

namespace POS\Shared\DTO;

final readonly class ResponseListing
{
    public function __construct(
        public ResponseStatus $status,
        public Pagination $pagination,
        public array $data,
    ) {
    }

    public static function create(
        ResponseStatus $status,
        array $data = [],
        int $total = 0,
        array $filters = []
    ): self {
        $limit = min(!isset($filters['limit']) || (int)$filters['limit'] < 1 ? 30 : (int)$filters['limit'], 500);

        return new self(
            $status,
            new Pagination(
                total: $total,
                perPage: $limit,
                currentPage: (int)(!isset($filters['page']) ? 1 : (max((int)$filters['page'], 1))),
                lastPage: (int)ceil($total / $limit)
            ),
            $data
        );
    }
}