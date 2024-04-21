<?php

declare(strict_types=1);

namespace POS\Shared\Connector\Filters\Abstracts;

abstract class AbstractFilter
{
    public function __construct(
        protected string $operator,
    ) {
    }

    public function getOperator(): string
    {
        return $this->operator;
    }
}
