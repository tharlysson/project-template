<?php

namespace POS\Shared\Connector\Filters;

use POS\Shared\Connector\Filters\Abstracts\AbstractFilterConditional;
use POS\Shared\Connector\Filters\Abstracts\Filter;
use POS\Shared\Exceptions\FilterTypeException;

final class FilterList extends AbstractFilterConditional
{
    public function __construct(
        protected mixed $value,
    ) {
        if (!is_array($value)) {
            throw new FilterTypeException($value);
        }
        parent::__construct(Filter::OPERATOR_LIST, $this->value);
    }
}
