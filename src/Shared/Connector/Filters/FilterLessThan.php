<?php

declare(strict_types=1);

namespace POS\Shared\Connector\Filters;

use POS\Shared\Connector\Filters\Abstracts\AbstractFilterSearchValue;
use POS\Shared\Connector\Filters\Abstracts\Filter;
use POS\Shared\Exceptions\FilterTypeException;

final class FilterLessThan extends AbstractFilterSearchValue
{
    public function __construct(
        protected string $attribute,
        protected mixed $value,
    ) {
        if (is_array($value)) {
            throw new FilterTypeException();
        }
        parent::__construct(Filter::OPERATOR_LESS_THAN, $this->$attribute, $this->value);
    }
}
