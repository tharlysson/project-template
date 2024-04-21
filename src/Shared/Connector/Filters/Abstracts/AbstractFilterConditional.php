<?php

declare(strict_types=1);

namespace POS\Shared\Connector\Filters\Abstracts;

abstract class AbstractFilterConditional extends AbstractFilter
{
    public function __construct(
        protected string $operator,
        protected mixed $value,
    ) {
        parent::__construct($this->operator);
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}
