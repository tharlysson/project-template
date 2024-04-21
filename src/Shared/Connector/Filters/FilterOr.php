<?php

declare(strict_types=1);

namespace POS\Shared\Connector\Filters;

use POS\Shared\Connector\Filters\Abstracts\AbstractFilterConditional;
use POS\Shared\Connector\Filters\Abstracts\Filter;
use POS\Shared\Exceptions\FilterTypeException;

final class FilterOr extends AbstractFilterConditional
{
    public function __construct(
        protected mixed $value,
    ) {
        if (is_array($value)) {
            throw new FilterTypeException();
        }
        parent::__construct(Filter::OPERATOR_CONDITIONS_OR, $this->value);
    }
}
