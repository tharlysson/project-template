<?php

declare(strict_types=1);

namespace POS\Shared\DTO;

final readonly class Response
{
    public function __construct(
        public ResponseStatus $status,
        public string|array|object $data,
    ) {
    }

    public static function create(ResponseStatus $status, string|array|object $data): self
    {
        return new self($status, $data);
    }
}