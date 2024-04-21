<?php

declare(strict_types=1);

namespace POS\Shared\Connector\Filters\Abstracts;

abstract class AbstractFilterSearch extends AbstractFilter
{
    public function __construct(
        protected string $operator,
        protected string $attribute,
    ) {
        parent::__construct($this->operator);
    }

    public function getAttribute(): string
    {
        return $this->attribute;
    }
}
