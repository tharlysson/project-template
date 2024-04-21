<?php

declare(strict_types=1);

namespace POS\Shared\Connector\Filters;

use POS\Shared\Connector\Filters\Abstracts\AbstractFilterSearchValue;
use POS\Shared\Connector\Filters\Abstracts\Filter;
use POS\Shared\Exceptions\FilterTypeException;

final class FilterNotEqual extends AbstractFilterSearchValue
{
    public function __construct(
        protected string $attribute,
        protected mixed $value,
    ) {
        if (is_array($value)) {
            throw new FilterTypeException();
        }
        parent::__construct(Filter::OPERATOR_NOT_EQUAL, $this->$attribute, $this->value);
    }
}
