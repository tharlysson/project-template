<?php

declare(strict_types=1);

namespace POS\Shared\Connector\Filters;

use POS\Shared\Connector\Filters\Abstracts\AbstractFilterSearchValue;
use POS\Shared\Connector\Filters\Abstracts\Filter;
use POS\Shared\Exceptions\FilterTypeException;

final class FilterLike extends AbstractFilterSearchValue
{
    public function __construct(
        protected string $attribute,
        protected mixed $value,
    ) {
        if (!is_string($value)) {
            throw new FilterTypeException($value);
        }
        parent::__construct(Filter::OPERATOR_LIKE, $this->attribute, "'%$this->value%'");
    }
}
