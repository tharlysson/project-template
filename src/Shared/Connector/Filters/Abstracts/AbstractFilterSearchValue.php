<?php

declare(strict_types=1);

namespace POS\Shared\Connector\Filters\Abstracts;

abstract class AbstractFilterSearchValue extends AbstractFilterSearch
{
    public function __construct(
        protected string $operator,
        protected string $attribute,
        protected mixed $value,
    ) {
        parent::__construct($this->operator, $this->attribute);
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}
